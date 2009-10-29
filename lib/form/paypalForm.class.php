<?php
class PaypalForm extends sfForm
{
	private $paypalDataFields 	= array();
	private $button				= null;
	private $host				= null;
	private $paypalFormData 	= true;
	
	
	public function __construct($paypalData)
	{
		$this->setData( $paypalData );
		parent::__construct();
	}

	public function configure()
	{
		$widgets  		= array();
		$validators 	= array();

		$this->paypalCheckMandatoryFields();

		if( $this->paypalFormData )
		{
			if(is_array($this->paypalDataFields) && count($this->paypalDataFields) > 0)
			{
				foreach($this->paypalDataFields as $parameter => $value)
				{
					$widgets[$parameter]  	= new sfWidgetFormInputHidden();
					$validators[$parameter] = new sfValidatorPass();
				}
			}
		}
		//********
		//** Set Widgets
		//********
		$this->setWidgets($widgets);

		//********
		//** Set Validators
		//********
		$this->setValidators($validators);

		$this->setDefaults($this->paypalDataFields);
	}
	
	public function paypalCheckMandatoryFields()
	{		
		foreach( sfConfig::get( 'app_paypal_form_mandatory_fields' ) as $field )
		{
			if( !array_key_exists( $field, $this->paypalDataFields) or $this->paypalDataFields[$field]==""  )
			{
				$this->paypalFormData = false;
			}
		}
	}
	
	public function render($attributes = array())
	{
		if($this->paypalFormData===true)
		{
			$return =  '<form action="'.$this->host.'" method="post">'.
	        parent::render($attributes = array()).
	        '<input name="submit" src="'.$this->button.'" type="image" style="width: auto;"/>
	        <img src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" border="0" alt="" width="1" height="1" />
	       </form>';
		}
		else
		{
			return __('Erreur, impossible de payer avec paypal');
		}
		return $return;	
	}
	
	protected function setData( $paypalData )
	{	
		$this->host = $paypalData['host'];
		unset($paypalData['host']);
		$this->button = $paypalData['button'];
		unset($paypalData['button']);
		
		$this->paypalDataFields = $paypalData;
	}
}
?>
