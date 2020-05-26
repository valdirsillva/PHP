<?php

namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;


/**
* 06/04/2020
*
*/


class CheckButton extends Field implements FormElementInterface 
{
	public function show() 
	{
		// Atribui propriedades as TAGs
		$tag = new Element('input');
		$tag->class = 'field';         // class CSS
		$tag->name  = $this->name;     // nome TAG
		$tag->value = $this->value;    // valor
		$tag->type  = 'checkbox';      // tipo input
		
		/**
		* Se o campo não for editável
		*/
		if(!parent::getEditable()) {
		  // Desabilita TAG input
		  $tag->readonly = "1";	
		}

		if($this->properties) {
           foreach($this->properties as $property => $value) {
             $tag->$property = $value;
           }
		}
		$tag->show(); // exibe a tag
	}
}
