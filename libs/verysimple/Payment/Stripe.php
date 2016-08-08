<?php
/** @package    verysimple::Payment */

/** import supporting libraries */
require_once("PaymentProcessor.php");
require_once("Stripe/init.php");

/**
 * Stripe extends the generic PaymentProcessor object to process
 * a PaymentRequest through the Stripe local API.
 *
 * @package    verysimple::Payment
 * @author     VerySimple Inc.
 * @copyright  1997-2012 VerySimple, Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    1.0
 */
class Stripe extends PaymentProcessor
{
	// @var string The Stripe API key to be used for requests.
    public static $apiKey;

    // @var string The base URL for the Stripe API.
    public static $apiBase = 'https://api.stripe.com';

    // @var string The base URL for the Stripe API uploads endpoint.
    public static $apiUploadBase = 'https://uploads.stripe.com';

    // @var string|null The version of the Stripe API to use for requests.
    public static $apiVersion = null;

    // @var string|null The account ID for connected accounts requests.
    public static $accountId = null;

    // @var boolean Defaults to true.
    public static $verifySslCerts = true;

    const VERSION = '3.19.0';

    /**
     * @return string The API key used for requests.
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion The API version to use for requests.
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * @return boolean
     */
    public static function getVerifySslCerts()
    {
        return self::$verifySslCerts;
    }

    /**
     * @param boolean $verify
     */
    public static function setVerifySslCerts($verify)
    {
        self::$verifySslCerts = $verify;
    }

    /**
     * @return string | null The Stripe account ID for connected account
     *   requests.
     */
    public static function getAccountId()
    {
        return self::$accountId;
    }

    /**
     * @param string $accountId The Stripe account ID to set for connected
     *   account requests.
     */
    public static function setAccountId($accountId)
    {
        self::$accountId = $accountId;
    }
    
	/**
	 * Process a PaymentRequest
	 * @param PaymentRequest $req Request object to be processed
	 * @return PaymentResponse
	 */
	function Process(PaymentRequest $req)
	{
        $resp = new PaymentResponse();

		try {
			$charge = \Stripe\Charge::create(array(
					"amount" => $req->TransactionAmount * 100, // amount in cents, again
					"currency" => "usd",
					"source" => $req->CCNumber,
					"description" => $req->OrderDescription
			));
		} catch(\Stripe\Error\Base $e) {
			// The card has been declined
			$resp->IsSuccess = false;
            $resp->ResponseCode = $e->httpStatus;
            $resp->ResponseMessage = $e->getMessage();
            return $resp;
		}

        $resp->IsSuccess = true;
        $resp->TransactionId = $charge->id;
        $resp->ResponseCode = $charge->id;
        $resp->ResponseMessage = "Charge of $req->TransactionAmount Posted";

        return $resp;
	}
	
	/**
	 * Called on contruction
	 * @param bool $test  set to true to enable test mode.  default = false
	 */
	function Init($testmode)
	{
		// TODO
	}
	
	/**
	 * @see PaymentProcessor::Refund()
	 */
	function Refund(RefundRequest $req)
	{
		throw new Exception("not implemented, use Stripe");
	}
}
?>