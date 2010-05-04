<?php

class csHyperwordActions extends sfActions
{
	public function executeProcess(sfWebRequest $request)
	{
		$word = $request->getParameter('word');
		$this->throwErrorUnless($word, 'No Hyperword to process!');
		$hyperword = Doctrine::getTable('Hyperword')->findOneByName($word);
		$this->throwErrorUnless($hyperword->id, 'Word has no matches');
		$routeMethod = $hyperword->getObject()->getRouteMethod();
		$this->redirect($hyperword->getObject()->$routeMethod());
	}
	public function throwErrorUnless($bool, $error = '')
	{
		if(!$bool)
		{
			throw new sfException($error);
		}
	}
}