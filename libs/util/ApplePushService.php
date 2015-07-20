<?php
/** @package    util */

require_once 'verysimple/Util/ExceptionThrower.php';
require_once 'ApnsPHP/AutoLoad.php';

/**
* Apple Push Service for sending push notifications to iOS devices.  This has been refactored
* for backwards compatibility, but internally is now an adapter for the ApnsPHP library
* @package    util
* @author     VerySimple Inc.
* @copyright  1997-2013 VerySimple, Inc.
* @license    http://www.gnu.org/licenses/lgpl.html  LGPL
* @version    1.0
* 
* @example <code>
* $service = new ApplePushService($certFilePath,$passPhrase,$useSandBoxMode);
* $result = $service->Send($token,'This is the message');
* print_r($result);
* </code>
*/
class ApplePushService
{

	private $certFilePath;
	private $certPassphrase;
	private $sandboxMode;
	
	/**
	 * 
	 * @param string $certFilePath path to the PEM certificate file provided by Apple
	 * @param string $certPassphrase the pass phrase for the cert
	 * @param bool $sandboxMode true to use sandbox mode (dev)
	 */
	public function __construct($certFilePath,$certPassphrase,$sandboxMode=false)
	{
		$this->certFilePath = $certFilePath;
		$this->certPassphrase = $certPassphrase;
		$this->sandboxMode = $sandboxMode;
	}
	
	/**
	 * Send a push notification
	 * @param string $deviceToken the push token for the mobile device
	 * @param string $message the message to display
	 * @param string $alertSound the audio file to play, otherwise use the default sound
	 * @param string $unlockText if the device is locked, show "Slide to XXX" where XXX is the unlockText
	 * @param int $badgeCount the number that should be shown on the springboard badge
	 */
	public function Send($deviceToken, $messageText, $alertSound='default', $unlockText = '', $badgeCount=0)
	{
		return $this->SendWithApns($deviceToken, $messageText, $alertSound, $unlockText, $badgeCount);
	}
	
	/**
	 * Send a push notification using ApnsPHP Library
	 * @param string $deviceToken the push token for the mobile device
	 * @param string $message the message to display
	 * @param string $alertSound the audio file to play, otherwise use the default sound
	 * @param string $unlockText if the device is locked, show "Slide to XXX" where XXX is the unlockText
	 * @param int $badgeCount the number that should be shown on the springboard badge
	 */
	public function SendWithApns($deviceToken, $messageText, $alertSound='default', $unlockText = '', $badgeCount=0)
	{
		$output = new stdClass();
		$output->date = date('Y-m-d H:i:s');
		$output->success = false;
		$output->message = '';
	
		$push = new ApnsPHP_Push(
				$sandboxMode ? ApnsPHP_Abstract::ENVIRONMENT_SANDBOX : ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
				$this->certFilePath
		);
		$push->setProviderCertificatePassphrase($this->certPassphrase);
		$push->connect();
	
		$message = new ApnsPHP_Message_Custom($deviceToken);
		$message->setCustomIdentifier("message-1"); // used to get error details if multiple messages are being sent
		$message->setText($messageText);
		$message->setSound($alertSound);
		$message->setExpiry(10); // timeout for connecting to push service
		$message->setActionLocKey($unlockText); // appended to "slide to " in the main unlock bar
		// $message->setLocKey(''); // override the text on the app lockscreen message
		$message->setBadge($badgeCount);
	
		$push->add($message);
		$push->send();
		$push->disconnect();
	
		// Examine the error message container
		$errors = $push->getErrors();
		$output->success = !empty($errors);
	
		if (!$output->success) {
			$output->message = print_r($errors,1);
		}
	
		return $output;
	}
	
	/**
	 * Send a push notification directly using a socket
	 * @param string $deviceToken the push token for the mobile device
	 * @param string $message the message to display
	 * @param string $alertSound the audio file to play, otherwise use the default sound
	 * @param string $unlockText if the device is locked, show "Slide to XXX" where XXX is the unlockText
	 * @param int $badgeCount the number that should be shown on the springboard badge
	 * @deprecated use Send instead
	 */
	public function SendWithSocket($deviceToken, $message, $alertSound='default', $unlockText = '', $badgeCount=0)
	{
		
		$gatewayUrl = $this->sandboxMode
			? "ssl://gateway.sandbox.push.apple.com:2195"
			: "ssl://gateway.push.apple.com:2195";
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certFilePath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->certPassphrase);
		
		$output = new stdClass();
		$output->date = date('Y-m-d H:i:s');
		
		$fp = null;
		
		try {
			// Open a connection to the APNS server
			$fp = stream_socket_client(
				$gatewayUrl, $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		catch (Exception $ex) {
			$output->success = false;
			$output->message = $ex->getMessage();
			return $output;
		}
		
		stream_set_blocking ($fp, 0); //This allows fread() to return right away when there are no errors. But it can also miss errors during last seconds of sending, as there is a delay before error is returned. Workaround is to pause briefly AFTER sending last notification, and then do one more fread() to see if anything else is there.
		
		if (!$fp)
		{
			$output->success = false;
			$output->message = "Connection Failed: $err $errstr";
		}
		else
		{
			$apple_expiry = time() + (10 * 24 * 60 * 60); //Keep push alive (waiting for delivery) for 10 days

			// format the body based on whether an unlock message was specified
			$alert = $unlockText 
				? array('body'=>$message,'action-loc-key'=>$unlockText)
				: $message;

			// Create the payload body
			$body['aps'] = array(
					'alert' => $alert,
					'sound' => $alertSound,
					'badge' => $badgeCount
			);
		
			$apple_identifier = 1;
			
			// Encode the payload as JSON
			$payload = json_encode($body);
		
			// Build the binary notification
			ExceptionThrower::$IGNORE_DEPRECATED = true;
			ExceptionThrower::Start();
			
			try {
				
				//$msg = pack("C", 1) . pack("N", $apple_identifier) . pack("N", $apple_expiry) . pack("n", 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n", strlen($payload)) . $payload; //Enhanced Notification
				
				
				$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			
				// Send it to the server
				$result = fwrite($fp, $msg, strlen($msg));
				
				//echo("\nBEFORE FREED\n");
				
				stream_set_blocking($fp, 0);
				usleep(500000);
				$apple_error_response = fread($fp, 6);
				
				//echo("\nAFTER FREED\n"); die();
			
				if (!$result)
				{
					$output->success = false;
					$output->message = 'Communication Error: Unable to deliver message';
				}
				else
				{
					$output->success = true;
					$output->message = print_r($result,1) .' ::: '.  print_r($apple_error_response,1);
				}
			}
			catch (Exception $ex)
			{
				$output->success = false;
				$output->message = $ex->getMessage();
			}
			
			ExceptionThrower::Stop();
		}
		
		// Close the connection to the server
		fclose($fp);
		
		return $output;
		
	}
	
}

?>