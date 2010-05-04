<?php

/**
* 
*/
class Doctrine_Template_HasHyperwords extends Doctrine_Template
{
  /**
   * Array of Options
   */  
	protected $method_suffix = 'WithHyperwords';
	
  protected $_options = array('columns' => array(
																'hyperwords' =>  array(
																	'name' 		=> 'hyperwords',
																	'type' 		=> 'string',
																	'length'	=>  255,
		                              'alias'   =>  null,
		                              'options' =>  array()),
															), 'routeMethod' => 'getRoute');


  /**
   * Constructor for Categorizable Template
   *
   * @param array $options 
   * @return void
   * @author Brent Shaffer
   */
  public function __construct(array $options = array())
  {
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

	public function getRouteMethod()
	{
		return $this->_options['routeMethod'];
	}
  public function setup()
  {

  }

  /**
   * Set table definition for categorizable behavior
   *
   * @return void
   * @author Brent Shaffer
   */
  public function setTableDefinition()
  {
		foreach ($this->_options['columns'] as $key => $options) {

	    $name = $options['name'];

			if ($options['alias'])
	    {
	      $name .= ' as ' . $options['alias'];
	    }
			
	    $this->hasColumn($name, $options['type'], $options['length'], $options['options']);
		}
		
    $this->addListener(new Doctrine_Template_Listener_HasHyperwords($this->_options));
  }
	// // Intended to add 'WithHyperwords' method to pull hyperworded text 
	// public function __call($method, $arguments)
	// {
	// 	try
	// 	{
	// 		parent::__call($method, $arguments);
	// 	}
	// 	catch(Exception $e)
	// 	{
	// 		if(strpos($method, $this->method_suffix))
	// 		{
	// 			$method = str_replace($method, $this->method_suffix);
	// 			return HyperwordToolkit::getInstance()->encode((string)$this->getInvoker()->$method());
	// 		}
	// 		throw $e;
	//   }
	// }
}