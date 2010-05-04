csHyperwordPlugin
=================

Use hyperwords to link to pages and pieces of content.  This automates functionality similar to that seen on wikipedia.com

Examples:

      [php]
      echo HyperwordToolkit::getInstance()->parseBlock($sf_content, 'p, .description', $excludedWordsArray) ?>

This will take all hyperwords and link them if they are within a p tag or a div of class 'description'.  Any basic css selector
will work here.  You can force content only to render if in a div of class content by passing this selector as the second
argument ('div.content').

In your Model:

      [yaml]
      MyModel:
        actAs:
          HasHyperwords:              ~
          
          
This adds a 'hyperwords' field on your model, which contains a comma-delimited list of hyperwords associated with this model.
You must have a 'getRoute' method (or another method passed as a ___"route\_method"___ option to your behavior) in order to
redirect to this piece of content's "show" page (or wherever else you'd like it to redirect)

For Example:


      [php]
      // MyModel.class.php
      function getRoute()
      {
        return '@my_model_show?slug='.$this['slug'];
      }
      
If an instance of this model has its __hyperwords__ field set to "spontaneous combustion" and a block of content rendered by the
Hyperword Toolkit contains the phrase "spontaneous combustion", this phrase will be converted into a link to the appropriate route.


For Example:

    [php]
    $object = new WikipediaLink();
    $object['hyperwords'] = 'spontaneous combustion';
    
    // WikipediaLink.class.php
    function getRoute()
    {
      return sprintf('http://en.wikipedia.org/wiki/Special:Search?search=%s&go=Go', $this['hyperwords']);
    }
    
    // Your content
    $content = And with spontaneous combustion, you could explode while reading up on symfony plugins.
    echo HyperwordToolkit::getInstance()->parseBlock($content);
      
    // would render below:

> And with [spontaneous combustion](http://en.wikipedia.org/wiki/Special:Search?search=spontaneous+combustion&go=Go), you could explode while reading up on symfony plugins.