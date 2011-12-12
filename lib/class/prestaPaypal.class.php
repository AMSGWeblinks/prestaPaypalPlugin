<?php
class prestaPaypal
{
	/**
	 * API username to login to Paypal
	 *
	 * @var string
	 */
	private $api_username = null;

	/**
	 * API password to login to Paypal
	 *
	 * @var string
	 */
	private $api_password = null;

	/**
	 * Determines if transactions should be made live or just to test
	 *
	 * @var bool
	 */
	private $api_test = false;

	/**
	 * Full path of the certificate file to use during authentication
	 *
	 * @var string
	 */
	private $api_certificate = null;

	/**
	 * Signature to use during authentication
	 *
	 * @var string
	 */
	private $api_signature = null;

	/**
	 * Email of a third party doing transactions for
	 *
	 * @var string
	 */
	private $api_subject = null;

	/**
	 * Holds information for the transaction
	 *
	 * @var array
	 */
	private $api_data = array(
		'country'		=> 'FR', 
		'action'		=> 'Sale', 
		'noshipping'	=> '1'
	);

	/**
	 * Caller object
	 *
	 * @var object
	 */
	private $api_caller = null;

	/**
	 * Error string
	 *
	 * @var string
	 */
	private $api_error_string = '';

	/**
	 * API version
	 *
	 * @var string
	 */
	private $api_version = '2.0';

	/**
	 * Type of transaction to charge or approve funds
	 *
	 * @var string
	 */
	private $api_action = 'Sale';

	/**
	 * Currency
	 *
	 * @var string
	 */
	private $currency_id = 'EUR';
	
	const URL_PAYPAL			= 'www.paypal.com';
	const URL_SANDBOX_PAYPAL	= 'www.sandbox.paypal.com';
	
	
	/**
	 * PaypalDirect constructor
	 *
	 * @param string $api_path - Full path to PayPal's Direct Payment API
	 */
	public function __construct($path=null)
	{
		if( !is_null($path) )
		{
			// Get rid of the many warnings from the Paypal API
			error_reporting(E_ERROR | E_WARNING | E_PARSE);
			ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $path);
			require_once 'PayPal.php';
			require_once 'PayPal/Profile/Handler/Array.php';
			require_once 'PayPal/Profile/API.php';
		}
	}

	/**
	 * Sets the API's user name
	 *
	 * @param string $username - API's username
	 */
	public function setUserName($username)
	{
		$this->api_username = $username;
	}

	/**
	 * Sets the API's password
	 *
	 * @param string $password - API's password
	 */
	public function setPassword($password)
	{
		$this->api_password = $password;
	}


	/**
	 * Make transactions live or go through the sandbox
	 *
	 */
	public function setTestMode($val=true)
	{
		$this->api_test = ($val == true) ? true : false;
	}

	/**
	 * Make transactions live
	 *
	 */
	public function setLiveMode()
	{
		$this->api_test = false;
	}

	/**
	 * Sets the path of the certificate file for authentication
	 *
	 * @param string $certificate - Full path of certificate file
	 */
	public function setCertificate($certificate)
	{
		$this->api_certificate = $certificate;
	}

	/**
	 * Sets the signature for authentification
	 *
	 * @param string $signature - API's signature
	 */
	public function setSignature($signature)
	{
		$this->api_signature = $signature;
	}

	/**
	 * Sets the email of the client doing transactions for
	 *
	 * @param string $subject - Email of client doing transactions as
	 */
	public function setSubject($subject)
	{
		$this->api_subject = $subject;
	}

	/**
	 * Sets the total amount of transaction
	 *
	 * @param string $total - Total amount of the transaction
	 */
	public function setTransactionTotal($total)
	{
		$this->api_data['total'] = $total;
	}

	/**
	 * Sets the description of the transaction
	 *
	 * @param string $description - Transaction's description
	 */
	public function setTransactionDescription($description)
	{
		$this->api_data['description'] = $description;
	}

	/**
	 * Sets a custom string for the transaction
	 *
	 * @param string $custom - Custom string for the transaction
	 */
	public function setTransactionCustom($custom)
	{
		$this->api_data['custom'] = $custom;
	}

	/**
	 * Sets the billing first name
	 *
	 * @param string $firstname - Billing first name
	 */
	public function setBillingFirstName($firstname)
	{
		$this->api_data['firstname'] = $firstname;
	}

	/**
	 * Gets the billing first name
	 *
	 * @return string
	 */
	public function getBillingFirstName()
	{
		return $this->api_data['firstname'];
	}

	/**
	 * Sets the billing last name
	 *
	 * @param string $lastname - Billing last name
	 */
	public function setBillingLastName($lastname)
	{
		$this->api_data['lastname'] = $lastname;
	}

	/**
	 * Gets the billing last name
	 *
	 * @return string
	 */
	public function getBillingLastName()
	{
		return $this->getData('lastname');
	}

	/**
	 * Sets the billing address 1
	 *
	 * @param string $street - Billing address 1
	 */
	public function setBillingStreet1($street)
	{
		$this->api_data['street1'] = $street;
	}

	/**
	 * Gets the billing address 1
	 *
	 * @return string
	 */
	public function getBillingStreet1()
	{
		return $this->getData('street1');
	}

	/**
	 * Sets the billing address 2
	 *
	 * @param string $street - Billing address 2
	 */
	public function setBillingStreet2($street)
	{
		$this->api_data['street2'] = $street;
	}

	/**
	 * Gets the billing address 2
	 *
	 * @return string
	 */
	public function getBillingStreet2()
	{
		return $this->getData('street2');
	}

	/**
	 * Sets the billing city
	 *
	 * @param string $city - Billing city
	 */
	public function setBillingCity($city)
	{
		$this->api_data['city'] = $city;
	}

	/**
	 * Gets the billing city
	 *
	 * @return string
	 */
	public function getBillingCity()
	{
		return $this->getData('city');
	}

	/**
	 * Sets the billing state
	 *
	 * @param string $state - Billing state
	 */
	public function setBillingState($state)
	{
		$this->api_data['state'] = $state;
	}

	/**
	 * Gets the billing state
	 *
	 * @return string
	 */
	public function getBillingState()
	{
		return $this->getData('state');
	}

	/**
	 * Sets the billing zip code
	 *
	 * @param string $zip - Billing zip code
	 */
	public function setBillingZip($zip)
	{
		$this->api_data['zip'] = $zip;
	}

	/**
	 * Gets the billing zip code
	 *
	 * @return string
	 */
	public function getBillingZip()
	{
		return $this->getData('zip');
	}

	/**
	 * Sets the billing country
	 *
	 * @param string $country - Billing two-letter country
	 */
	public function setBillingCountry($country)
	{
		$this->api_data['country'] = $country;
	}

	/**
	 * Gets the billing country
	 *
	 * @return string
	 */
	public function getBillingCountry()
	{
		return $this->getData('country');
	}

	/**
	 * Sets the billing email
	 *
	 * @param string $email - Billing email
	 */
	public function setBillingEmail($email)
	{
		$this->api_data['email'] = $email;
	}

	/**
	 * Gets the billing email
	 *
	 * @return string
	 */
	public function getBillingEmail()
	{
		return $this->getData('email');
	}

	/**
	 * Sets the credit card type
	 *
	 * @param string $cctype - Credit card number type (Visa, MasterCard, Discover, Amex)
	 */
	public function setCardType($cctype)
	{
		$this->api_data['cctype'] = $cctype;
	}

	/**
	 * Sets the credit card number to charge
	 *
	 * @param string $ccnumber - Credit card number to charge
	 */
	public function setCardNumber($ccnumber)
	{
		$this->api_data['ccnumber'] = $ccnumber;
	}

	/**
	 * Sets the credit card expiration month
	 *
	 * @param string $ccexpmonth - Credit card expiration month
	 */
	public function setCardExpirationMonth($ccexpmonth)
	{
		$this->api_data['ccexpmonth'] = $ccexpmonth;
	}


	/**
	 * Sets the credit card expiration year
	 *
	 * @param string $ccexpyear - Credit card expiration year
	 */
	public function setCardExpirationYear($ccexpyear)
	{
		$this->api_data['ccexpyear'] = $ccexpyear;
	}

	/**
	 * Sets the credit card verification number
	 *
	 * @param string $ccvnumber - Credit card verification number
	 */
	public function setCardVerificationNumber($ccvnumber)
	{
		$this->api_data['ccvnumber'] = $ccvnumber;
	}

	/**
	 * Sets the remote IP of the buyer (For Direct payment)
	 *
	 * @param string $ccvnumber - Remote IP of buyer
	 */
	public function setBuyerIP($ip)
	{
		$this->api_data['ip'] = $ip;
	}

	/**
	 * Turns on shipping (For Express Checkout)
	 *
	 */
	public function setShippingOn()
	{
		$this->api_data['noshipping'] = '0';
	}

	/**
	 * Turns off shipping (For Express Checkout)
	 *
	 */
	public function setShippingOff()
	{
		$this->api_data['noshipping'] = '1';
	}

	/**
	 * Sets cancel URL (For Express Checkout)
	 *
	 * @param string $url - Absolute URL if user cancels payment
	 */
	public function setCancelURL($url)
	{
		$this->api_data['cancelurl'] = $url;
	}

	/**
	 * Sets return URL (For Express Checkout)
	 *
	 * @param string $url - Absolute URL to return after succesful payment
	 */
	public function setReturnURL($url)
	{
		$this->api_data['returnurl'] = $url;
	}

	/**
	 * Sets the API version
	 *
	 * @param string $version - API version
	 */
	public function setApiVersion($version)
	{
		$this->api_version = $version;
	}

	/**
	 * Gets the last error
	 *
	 * @return string
	 */
	public function getErrorString()
	{
		return $this->api_error_string;
	}

	/**
	 * Sets transactions to charge funds
	 */
	private function setActionSale()
	{
		$this->api_action = 'Sale';
	}

	/**
	 * Sets transactions to authorize funds
	 */
	private function setActionAuthorize()
	{
		$this->api_action = 'Authorize';
	}

	/**
	 * Gets the data from the request or null if not set
	 *
	 * @param string $field - Hash field to look for
	 * @return string
	 */
	private function getData($field)
	{
		if ( isset($this->api_data[$field]) )
		{
			return $this->api_data[$field];
		}
		else
		{
			return null;
		}
	}

	/**
	 * Get an instance of a caller
	 *
	 * @return bool
	 */
	private function getCaller()
	{
		$handler =& ProfileHandler_Array::getInstance(
		array(	'username'        => $this->api_username,
				'certificateFile' => $this->api_certificate,
				'signature'       => $this->api_signature,
				'subject'         => $this->api_subject,
				'environment'     => (($this->api_test)?'Sandbox':'Live')
		)
		);
		$profile =& APIProfile::getInstance($this->api_username, $handler);
		$profile->setAPIPassword($this->api_password);
		$this->api_caller =& PayPal::getCallerServices($profile);

		if ( PayPal::isError($this->api_caller) )
		{
			$this->api_error_string = $this->api_caller->getMessage();
			return false;
		}
		return true;
	}

	/**
	 * Execute a remote and final call
	 *
	 * @return bool
	 */
	private function execute($request, $type)
	{
		$details =& PayPal::getType('Do' . $type . 'RequestType');
		$details->setVersion($this->api_version);
		$func = 'setDo' . $type . 'RequestDetails';
		$details->$func($request);
		$func = 'Do' . $type;
		$final_req = $this->api_caller->$func($details);

		if ( $final_req->Ack == 'Success' )
		{
			return true;
		}
		else if ( is_array($final_req->Errors) )
		{
			foreach ( $final_req->Errors as $error )
			{
				$this->api_error_string .= $error->LongMessage . ' ';
			}
		}
		else
		{
			$this->api_error_string = $final_req->Errors->LongMessage;
		}
		return false;
	}

	/**
	 * Uses the Direct Paypal API to charge for funds
	 *
	 * @return bool
	 */
	public function chargeDirect()
	{
		if( !$this->getCaller() )
		{
			return false;
		}

		// Transaction information
		$BasicAmountType =& PayPal::getType('BasicAmountType');
		$BasicAmountType->setattr('currencyID', $this->currency_id);
		$BasicAmountType->setval($this->getData('total'));


		$PaymentDetailsType =& PayPal::getType('PaymentDetailsType');
		$PaymentDetailsType->setOrderTotal($BasicAmountType);
		$PaymentDetailsType->setOrderDescription($this->getData('description'));
		$PaymentDetailsType->setCustom($this->getData('custom'));

		// Billing information
		$PersonNameType =& PayPal::getType('PersonNameType');
		$PersonNameType->setFirstName($this->getBillingFirstName());
		$PersonNameType->setLastName($this->getBillingLastName());

		$AddressType =& PayPal::getType('AddressType');
		$AddressType->setStreet1($this->getBillingStreet1());
		$AddressType->setStreet2($this->getBillingStreet2());
		$AddressType->setCityName($this->getBillingCity());
		$AddressType->setStateOrProvince($this->getBillingState());
		$AddressType->setPostalCode($this->getBillingZip());
		$AddressType->setCountry($this->getBillingCountry());
		$PayerInfoType =& PayPal::getType('PayerInfoType');
		$PayerInfoType->setPayerName($PersonNameType);
		$PayerInfoType->setAddress($AddressType);

		// Credit card information
		$CreditCardDetailsType =& PayPal::getType('CreditCardDetailsType');
		$CreditCardDetailsType->setCardOwner($PayerInfoType);
		$CreditCardDetailsType->setCreditCardType($this->getData('cctype'));
		$CreditCardDetailsType->setCreditCardNumber($this->getData('ccnumber'));
		$CreditCardDetailsType->setExpMonth($this->getData('ccexpmonth'));
		$CreditCardDetailsType->setExpYear($this->getData('ccexpyear'));
		$CreditCardDetailsType->setCVV2($this->getData('ccvnumber'));

		// Create request
		$request =& PayPal::getType('DoDirectPaymentRequestDetailsType');
		$request->setPaymentAction($this->api_action);
		$request->setPaymentDetails($PaymentDetailsType);
		$request->setCreditCard($CreditCardDetailsType);
		$request->setIPAddress($this->getData('ip'));
		return $this->execute($request, 'DirectPayment');
	}

	/**
	 * Gets a Paypal destination URL to charge for funds
	 *
	 * @return string - Returns URL to redirect to or false if an error occurs
	 */
	public function GetExpressUrl()
	{
		if( !$this->getCaller() )
		{
			return false;
		}

		// Transaction information
		$BasicAmountType =& PayPal::getType('BasicAmountType');
		$BasicAmountType->setattr('currencyID', $this->currency_id);
		$BasicAmountType->setval($this->getData('total'));

		$ExpressCheckoutType =& PayPal::getType('SetExpressCheckoutRequestDetailsType');
		$ExpressCheckoutType->setNoShipping($this->getData('noshipping'));
		$ExpressCheckoutType->setCancelURL($this->getData('cancelurl'));
		$ExpressCheckoutType->setReturnURL($this->getData('returnurl'));
		$ExpressCheckoutType->setOrderTotal($BasicAmountType);

		$ExpressCheckoutRequestType =& PayPal::getType('SetExpressCheckoutRequestType');
		$ExpressCheckoutRequestType->setVersion($this->api_version);
		$ExpressCheckoutRequestType->setSetExpressCheckoutRequestDetails($ExpressCheckoutType);

		$request = $this->api_caller->SetExpressCheckout($ExpressCheckoutRequestType);

		if ( $request->Ack == 'Success' )
		{
			$host = self::URL_PAYPAL;
			if( $this->api_test )
			{
				$host = self::URL_SANDBOX_PAYPAL;
			}
			return 'https://' . $host . '/cgi-bin/webscr?cmd=_express-checkout&token=' . $request->Token;
		}
		else
		{
			if ( is_array($request->Errors) )
			{
				foreach ( $request->Errors as $error )
				{
					$this->api_error_string .= $error->LongMessage . ' ';
				}
			}
			else
			{
				$this->api_error_string = PEAR::isError($request) ? $request->getMessage()
				                                                  : $request->Errors->LongMessage;
			}
			return false;
		}
	}

	/**
	 * Charges funds from Paypal from the given token
	 *
	 * @param string $token - Token given by Paypal on a GET request on the return URL
	 * @return bool
	 */
	public function chargeExpressCheckout($token)
	{
		if( !$this->getCaller() )
		{
			return false;
		}

		$ExpressCheckoutDetailsRequestType =& PayPal::getType('GetExpressCheckoutDetailsRequestType');
		$ExpressCheckoutDetailsRequestType->setToken($token);

		// Execute the call
		$r = $this->api_caller->GetExpressCheckoutDetails($ExpressCheckoutDetailsRequestType);
		if ( $r->Ack == 'Success' )
		{
			$details = $r->getGetExpressCheckoutDetailsResponseDetails();

			$buyer_info = $details->getPayerInfo();
			$buyer_name = $buyer_info->getPayerName();
			$buyer_address = $buyer_info->getAddress();

			$this->setBillingFirstName($buyer_name->FirstName);
			$this->setBillingLastName($buyer_name->LastName);
			$this->setBillingStreet1($buyer_address->Street1);
			$this->setBillingStreet2($buyer_address->Street2);
			$this->setBillingCity($buyer_address->CityName);
			$this->setBillingState($buyer_address->StateOrProvince);
			$this->setBillingZip($buyer_address->PostalCode);
			$this->setBillingCountry($buyer_address->Country);
			$this->setBillingEmail($buyer_info->Payer);

			$BasicAmountType =& PayPal::getType('BasicAmountType');
			$BasicAmountType->setattr('currencyID', $this->currency_id);
			$BasicAmountType->setval($this->getData('total'));

			$AddressType =& PayPal::getType('AddressType');
			$AddressType->setStreet1($this->getBillingStreet1());
			$AddressType->setStreet2($this->getBillingStreet2());
			$AddressType->setCityName($this->getBillingCity());
			$AddressType->setStateOrProvince($this->getBillingState());
			$AddressType->setPostalCode($this->getBillingZip());
			$AddressType->setCountry($this->getBillingCountry());

			$PaymentDetailsType =& PayPal::getType('PaymentDetailsType');
			$PaymentDetailsType->setOrderTotal($BasicAmountType);
			$PaymentDetailsType->setOrderDescription($this->getData('description'));
			$PaymentDetailsType->setCustom($this->getData('custom'));
			$PaymentDetailsType->setShipToAddress($AddressType);

			$request =& PayPal::getType('DoExpressCheckoutPaymentRequestDetailsType');
			$request->setToken($token);
			$request->setPayerID($buyer_info->PayerID);
			$request->setPaymentAction($this->api_action);
			$request->setPaymentDetails($PaymentDetailsType);
			return $this->execute($request, 'ExpressCheckoutPayment');
		}
		else
		{
			$this->api_error_string = $request->getMessage();
			return false;
		}
	}
	
	
	public function getFormStandardPayment( array $values )
	{
		$form = new PaypalForm($values);
		return $form;
	}
	
	/**
	 * Setting currency id
	 *
	 * @author Paweł Wilk <p.wilk@alwasatt.com>
	 * 
	 * @param string $currencyId
	 */
	public function setCurrencyId($currencyId)
	{
    $this->currency_id = $currencyId;
	}
}
