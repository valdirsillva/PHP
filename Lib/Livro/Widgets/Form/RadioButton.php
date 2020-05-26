<?php

namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;
/**
* 07/04/2020
*
*/

class RadioButton extends Field implements FormElementInterface 
{
	public function show() 
	{
		$tag = new Element('input');
		$tag->class = 'field';
		$tag->name  = $this->name;
		$tag->value = $this->value;
		$tag->type  = 'radio';

		/** Se o campo for editável **/
		if(!parent::getEditable()) {
			$tag->readonly = "1";
		}

		if($this->properties) {
           foreach($this->properties as $property => $value ) {
              $tag->$property = $value;
           } 
		}
		$tag->show(); /** ** exibe a tag **/
	}
}