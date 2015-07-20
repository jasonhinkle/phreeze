<?php
/** @package    util */

require_once 'verysimple/Util/ExceptionThrower.php';
require_once 'ApnsPHP/Autoload.php';

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
	
	public static $ERRORS = array(
		0   => '', // No errors encountered
		1   => 'Processing error',
		2   => 'Missing device token',
		3   => 'Missing topic',
		4   => 'Missing payload',
		5   => 'Invalid token size',
		6   => 'Invalid topic size',
		7   => 'Invalid payload size',
		8   => 'Invalid token',
		10   => 'Shutdown',
		255   => 'Unknown'
	);
	
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
		return $this->SendWithSocket($deviceToken, $messageText, $alertSound, $unlockText, $badgeCount);
	}
	
	/**
	 * Send a push notification directly using a socket
	 * @param string $deviceToken the push token for the mobile device
	 * @param string $message the message to display
	 * @param string $alertSound the audio file to play, otherwise use the default sound
	 * @param string $unlockText if the device is locked, show "Slide to XXX" where XXX is the unlockText
	 * @param int $badgeCount the number that should be shown on the springboard badge
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
		
		$fh = null;
		$errorMesssage = NULL;
		
		try {
			// Open a connection to the APNS server
			$fh = stream_socket_client(
				$gatewayUrl, $err,
				$errorMesssage, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		}
		catch (Exception $ex) {
			$errorMesssage = $ex->getMessage();
		}
		
		if ($errorMesssage || !$fh)
		{
			$output->success = false;
			$output->message = "Connection Failed: $errorMesssage";
		}
		else
		{
			$appleExpiry = time() + (10 * 24 * 60 * 60); //Keep push alive (waiting for delivery) for 10 days

			// format the body based on whether an unlock message was specified
			$alert = $unlockText 
				? array('body'=>$message,'action-loc-key'=>$unlockText)
				: $message;

			// Create the payload body
			$body['aps'] = array(
					'alert' => $alert,
					'sound' => $alertSound,
					'badge' => (int)$badgeCount
			);
		
			// Encode the payload as JSON
			$payload = json_encode($body);
		
			// Build the binary notification
			ExceptionThrower::$IGNORE_DEPRECATED = true;
			ExceptionThrower::Start();

			try {
				
				// @see https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/CommunicatingWIthAPS.html
				$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
				fwrite($fh, $msg, strlen($msg));

				$response = $this->getResponse($fh);

				if (!$response) {
					// everything is cool
					$output->success = true;
					$output->message = 'Message sent';
				}
				else {
					$output->success = false;
					$output->message = 'Push notification failed with response: ' . $response;
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
		if ($fh) @fclose($fh);
		
		return $output;
		
	}
	
	/**
	 * Recursive function to check if there is anything in the stream to read.
	 * Will wait for up to 
	 * 
	 * @param int $fh file handle
	 * @param number $timeout (in seconds) default = 5
	 * @param number $index (used internally as recursion counter - do not provide a value)
	 * @return string empty string for success, or a text message for failure
	 */
	private function getResponse($fh,$timeout = 5,$index = 0)
	{
		$read = array($fh);
		$write  = NULL;
		$except = NULL;
		$num_changed_streams = stream_select($read, $write, $except, 0);

		if ($num_changed_streams > 0) {
			$errorResponse = fread($fh, 6);
			$reason = '';
			if ($errorResponse) {
				// response is binary, we have to unpack it into an array
				$response = unpack('Ccommand/Cstatus_code/Nidentifier',$errorResponse);
				$code = array_key_exists('status_code', $response) ? $response['status_code'] : 255;
				$reason = array_key_exists($code, self::$ERRORS) ? self::$ERRORS[$code] : "Code $code";
			}
			return $reason;
		}
		
		// 10th time through, we give up
		if ($index >= ($timeout * 10)) return '';
		
		usleep(100000);
		return $this->getResponse($fh,$timeout,$index+1);
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
				$this->sandboxMode ? ApnsPHP_Abstract::ENVIRONMENT_SANDBOX : ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
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
}
?>