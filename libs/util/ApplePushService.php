<?php
/** @package    util */

/**
* Apple Push Service for sending push notifications to iOS devices
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
	private $gatewayUrl;
	
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
		
		$this->gatewayUrl = $sandboxMode
			? "ssl://gateway.sandbox.push.apple.com:2195"
			: "ssl://gateway.push.apple.com:2195";
	}
	

	/**
	 * Send a push notification
	 * @param string $deviceToken the push token for the mobile device
	 * @param string $message the message to display
	 * @param string $alertSound the audio file to play, otherwise use the default sound
	 * @param string $unlockText if the device is locked, show "Slide to XXX" where XXX is the unlockText
	 * @param int $badgeCount the number that should be shown on the springboard badge
	 */
	public function Send($deviceToken, $message, $alertSound='default', $unlockText = '', $badgeCount=0)
	{
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certFilePath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->certPassphrase);
		
		$output = new stdClass();
		$output->date = date('Y-m-d H:i:s');
		
		// Open a connection to the APNS server
		$fp = stream_socket_client(
				$this->gatewayUrl, $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp)
		{
			$output->success = false;
			$output->message = "Connection Failed: $err $errstr";
		}
		else
		{
		
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
		
			// Encode the payload as JSON
			$payload = json_encode($body);
		
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		
			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));
		
			if (!$result)
			{
				$output->success = false;
				$output->message = 'Communication Error: Unable to deliver message';
			}
			else
			{
				$output->success = true;
				$output->message = 'Message accepted';
			}
		}
		
		// Close the connection to the server
		fclose($fp);
		
		return $output;
		
	}
	
}

?>