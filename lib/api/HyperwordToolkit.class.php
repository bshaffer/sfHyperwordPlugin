<?php

require_once dirname(__FILE__).'/../vendor/simplehtmldom/simple_html_dom.php';

/**
* 
*/
class HyperwordToolkit
{
 	protected static $instance;
 	protected $_decorator = '';
  protected $route;
  protected $_words = array();
  
  protected function __construct()
  {
		$this->route = '@hyperword_processor';
		$this->_decorator = $this->getDecorator();
  }
  
  public static function getInstance() 
  {
    if (!self::$instance instanceof self) 
		{ 
      self::$instance = new self;
    }
    
    return self::$instance;
  }

	public function safeEncode($text)
	{
	  $matches = array();
	  preg_match("/>([^<]*)</", $text, $matches, PREG_OFFSET_CAPTURE);

    if (!$matches) 
    {
      return $this->encode($text);
    }

    $newtext = $matches && trim($matches[1][0]) ? str_replace($matches[1][0], $this->encode($matches[1][0]), $text) : $text;    
    
	  while (preg_match("/>([^<]+)</", $text, $matches, PREG_OFFSET_CAPTURE, $matches[0][1]+1)) {	    
      if (!$matches || !trim($matches[1][0])) continue;

      $newtext = str_replace($matches[0][0], $this->encode($matches[0][0]), $newtext);
	  }

	  return $newtext;
	}
	
	public function encode($text)
	{
		foreach ($this->_words as $index => $word) 
		{
		  if ($word) 
		  {
        $text = preg_replace("~$word~i", "%%HYPERWORD$index%%", $text, 1);
		  }
		}

    // Counts backward ( to avoid HYPERWORD1 from replacing HYPERWORD11 )
		for ($i=count($this->_words) - 1; $i >= 0; $i--) 
		{
		  if ($this->_words[$i] && strpos($text, "%%HYPERWORD$i%%") !== false) 
		  {
		    $text = str_replace("%%HYPERWORD$i%%", $this->decorate($this->_words[$i]), $text);
        $this->_words[$i] = false; // only link the first hyperword
		  }

		}
		
		return $text;
	}
	
	public function parseBlock($source, $selector = null, $excluded = '')
	{
		$this->_words = $this->getDefaultHyperwords($excluded);
		
		if($selector)
		{
			$dom = new simple_html_dom();
			$dom->load('<html><body>'.$source.'</body></html>');
			$blocks = $dom->find($selector); //"div[id=$class]"; 
			foreach ($blocks as $block) 
			{
				$new_block = $this->safeEncode($block);
				$source = str_replace($block, $new_block, $source);
			}
			return $source;
		}
		return $this->safeEncode($source);
	}
	
	
	public function decorate($hyperword)
	{
		return sprintf($this->_decorator, $hyperword, $hyperword);
	}
	
	public function getDecorator()
	{
		sfLoader::loadHelpers('Url');
		sfLoader::loadHelpers('Tag');
		$decorator = link_to('%s', '@hyperword_processor?word=%s', array('class' => 'hyperword'));
		return str_replace('%25s', '%s', $decorator);
	}
	
	public function getDefaultHyperwords($excluded = '')
	{
	  $q = Doctrine::getTable('Hyperword')->createQuery('h')->orderBy('LENGTH(h.name) DESC');
	  if ($excluded) 
	  {
      $q->whereNotIn('h.name', explode(', ', $excluded));
	  }
		return $q->execute();
	}
	
	static function trim_array($input)
	{
    if (!is_array($input))
        return trim($input);
 
    return array_map('HyperwordToolkit::trim_array', $input);
	}
	
	public function collectionToArray($collection)
	{
		$arr = array();
		foreach ($collection as $hyperword) 
		{
			$arr[] = $hyperword->getName();
		}
		return $arr;
	}
}