<?php
/** @package    util */

require_once 'verysimple/Util/ExceptionThrower.php';

/**
* Android Push Service for sending push notifications to GooglePlay-enabled devices
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
class AndroidPushService
{
	private $googlePushRegistrationId;
	private $gatewayUrl;

	/**
	 * Sets the gateway URL for sending messages
	 * @param string $apiKey the sender ID that matches to the google API project
	 */
	public function __construct($endpoint, $apiKey)
	{
		$this->gatewayUrl = $endpoint;
		$this->googlePushRegistrationId = $apiKey;
	}


	/**
	 * Send a push notification
	 * @param string $deviceToken the push token for the mobile device
	 * @param string $message the message to display
	 */
	public function Send($deviceToken, $message, $method = "HTTP")
	{
		$output = new stdClass();
		$output->date = date('Y-m-d H:i:s');

		// TODO: add in CCS with XMPP services alongside of the vanilla HTTP
		if ($method == "HTTP")
		{
			$fields = array(
				'registration_ids' => array($deviceToken),
				'data' => array("message" => $message)
			);

			$headers = array(
				'Authorization: key=' . $this->googlePushRegistrationId,
				'Content-Type: application/json'
			);

			// Open connection
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $this->gatewayUrl);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Disabling SSL Certificate support temporarily
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

			// Execute post
			$result = curl_exec($ch);

			if ($result === FALSE)
			{
				$output->success = false;
				$output->message = 'Communication Error: Unable to deliver message';
			}
			else
			{
				$output->success = true;
				$output->message = print_r($result,1);
			}

			curl_close($ch);
		}

		return $output;
	}

}

?>