<?php

// 
//  HasHyperwords.php
//  csHyperwordsPlugin
//  
//  Created by Brent Shaffer on 2009-02-22.
//  Copyright 2008 Centre{source}. All rights reserved.
// 

class Doctrine_Template_Listener_HasHyperwords extends Doctrine_Record_Listener
{
  /**
   * Array of Categorizable options
   */  
  protected $_options = array();


  /**
   * Constructor for Hyperwords Template
   *
   * @param array $options 
   * @return void
   * @author Brent Shaffer
   */  
  public function __construct(array $options)
  {
    $this->_options = $options;
  }


  /**
   * Set the position value automatically when a new Hyperwords object is created
   *
   * @param Doctrine_Event $event
   * @return void
   * @author Brent Shaffer
   */
  public function preInsert(Doctrine_Event $event)
  {
    // $object = $event->getInvoker();
  }
	
	//Add hyperwords to Hyperwords table if they don't exist
	public function postSave(Doctrine_Event $event)
	{
		$object = $event->getInvoker();
		$hyperwords = HyperwordToolkit::trim_array(explode(',', $object->getHyperwords()));
		$existing = Doctrine::getTable('Hyperword')->findForObjectAsArray($object); 

		$remove = array_filter(array_diff($existing, $hyperwords));
		$add 		= array_filter(array_diff($hyperwords, $existing));

		Doctrine::getTable('Hyperword')->createForObject($object, $add);
		Doctrine::getTable('Hyperword')->removeForObject($object, $remove);
	}
	
  /**
   *
   * @param string $Doctrine_Event 
   * @return void
   * @author Brent Shaffer
   */  
  public function postDelete(Doctrine_Event $event)
  {
		$object = $event->getInvoker();
		$hyperwords = HyperwordToolkit::trim_array(explode(',', $object->getHyperwords()));

		Doctrine::getTable('Hyperword')->removeForObject($object, $hyperwords);
  }
}
