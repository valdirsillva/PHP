<?php

namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;

/**
* 29/03/2020
*
*/

class Entry extends Field implements FormElementInterface 
{
	protected $properties;

	public function show() 
	{
		/** ** Atribui as prop da TAG **/
		$tag = new Element('input');
		$tag->class = 'field form-control';             /** Classe CSS **/
		$tag->name  = $this->name;         /** Nome TAG   **/
		$tag->value = $this->value;        /** Valor TAG  **/
		$tag->type  = 'text';              /** Tipo de input **/
		$tag->style = "width:{$this->size}"; /** Tamanho em pixels **/


		/** Se o campo não é editável **/
		if(!parent::getEditable()) {
            $tag->readonly = "1";
		}

		if($this->properties) {
           foreach($this->properties as $property => $value) {
               $tag->$property = $value;
           }
		}

		$tag->show(); /** exibe a tag **/
	}

}