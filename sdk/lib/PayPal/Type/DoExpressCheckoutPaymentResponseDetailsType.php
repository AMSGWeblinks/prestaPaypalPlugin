<?php
/**
 * @package PayPal
 */

/**
 * Make sure our parent class is defined.
 */
require_once 'PayPal/Type/XSDSimpleType.php';

/**
 * DoExpressCheckoutPaymentResponseDetailsType
 *
 * @package PayPal
 */
class DoExpressCheckoutPaymentResponseDetailsType extends XSDSimpleType
{
    /**
     * The timestamped token value that was returned by SetExpressCheckoutResponse and
     * passed on GetExpressCheckoutDetailsRequest.
     */
    var $Token;

    /**
     * Information about the transaction
     */
    var $PaymentInfo;

    var $BillingAgreementID;

    var $RedirectRequired;

    /**
     * Memo entered by sender in PayPal Review Page note field.
     */
    var $Note;

    function DoExpressCheckoutPaymentResponseDetailsType()
    {
        parent::XSDSimpleType();
        $this->_namespace = 'urn:ebay:apis:eBLBaseComponents';
        $this->_elements = array_merge($this->_elements,
            array (
              'Token' => 
              array (
                'required' => true,
                'type' => 'ExpressCheckoutTokenType',
                'namespace' => 'urn:ebay:apis:eBLBaseComponents',
              ),
              'PaymentInfo' => 
              array (
                'required' => true,
                'type' => 'PaymentInfoType',
                'namespace' => 'urn:ebay:apis:eBLBaseComponents',
              ),
              'BillingAgreementID' => 
              array (
                'required' => false,
                'type' => 'string',
                'namespace' => 'urn:ebay:apis:eBLBaseComponents',
              ),
              'RedirectRequired' => 
              array (
                'required' => false,
                'type' => 'string',
                'namespace' => 'urn:ebay:apis:eBLBaseComponents',
              ),
              'Note' => 
              array (
                'required' => false,
                'type' => 'string',
                'namespace' => 'urn:ebay:apis:eBLBaseComponents',
              ),
            ));
    }

    function getToken()
    {
        return $this->Token;
    }
    function setToken($Token, $charset = 'iso-8859-1')
    {
        $this->Token = $Token;
        $this->_elements['Token']['charset'] = $charset;
    }
    function getPaymentInfo()
    {
        return $this->PaymentInfo;
    }
    function setPaymentInfo($PaymentInfo, $charset = 'iso-8859-1')
    {
        $this->PaymentInfo = $PaymentInfo;
        $this->_elements['PaymentInfo']['charset'] = $charset;
    }
    function getBillingAgreementID()
    {
        return $this->BillingAgreementID;
    }
    function setBillingAgreementID($BillingAgreementID, $charset = 'iso-8859-1')
    {
        $this->BillingAgreementID = $BillingAgreementID;
        $this->_elements['BillingAgreementID']['charset'] = $charset;
    }
    function getRedirectRequired()
    {
        return $this->RedirectRequired;
    }
    function setRedirectRequired($RedirectRequired, $charset = 'iso-8859-1')
    {
        $this->RedirectRequired = $RedirectRequired;
        $this->_elements['RedirectRequired']['charset'] = $charset;
    }
    function getNote()
    {
        return $this->Note;
    }
    function setNote($Note, $charset = 'iso-8859-1')
    {
        $this->Note = $Note;
        $this->_elements['Note']['charset'] = $charset;
    }
}
