<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginHyperword extends BaseHyperword
{
	public function getObject()
	{
		return Doctrine::getTable($this->getObjectClass())->findOneById($this->getObjectId());
	}
}