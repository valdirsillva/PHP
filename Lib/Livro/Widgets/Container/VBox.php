<?php
namespace Livro\Widgets\Container;
use Livro\Widgets\Base\Element;

/**
* @param Data: 25/04/2020
* ValdirSilva
* 
*/

class VBox extends Element 
{
   private $body;
   private $footer;
   
   /**
   * Construtor
   *
   *
   */
   public function __construct() 
   {
   	  parent::__construct('div');
   	  $this->{'style'} = 'display: inline-block';
   }

   /**
   * Adiciona um elemento filho
   * @param $child Objeto filho
   *
   */
   public function add($child) 
   {
   	  $wrapper = new  Element('div');
   	  $wrapper->{'style'} = 'clear:both';
   	  $wrapper->add($child);
   	  parent::add($wrapper);
      return $wrapper;
   }
}