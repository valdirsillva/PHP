<?php

namespace Livro\Widgets\Container;
use Livro\Widgets\Base\Element;
/** 
Data: 08/03/2020 **/

class HBox extends Element 
{
   public function __construct() 
   {
      parent::__construct('div');
   }

   /** ** **/
   public function add($child) 
   {
   	  $wrapper = new Element('div');
   	  $wrapper->{'style'} = 'display:inline-block';
   	  $wrapper->add($child);
   	  parent::add($wrapper);
   	  return $wrapper;
   }
}