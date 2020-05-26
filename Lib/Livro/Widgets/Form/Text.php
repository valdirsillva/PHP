<?php

namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;


class Text extends Field implements FormElementInterface 
{
	private $width;
	private $height = 100;

	public function setSize($width, $height = NULL)
	{
        $this->size = $width;
        if(isset($height)) {
           $this->height = $height;
        }
	}

	public function show() 
	{
		$tag = new Element('textarea');
		$tag->class = 'field';        /** Classe CSS **/
		$tag->name  = $this->name;
		$tag->style = "width:{$this->size};height:{$this->height}";

		/** Se o campo não for editável **/
		if(!parent::getEditable()) {
           // Desabilita tag input
		   $tag->readonly = "1";	
		} 
		$tag->add(htmlspecialchars($this->value)); // add conteúdo ao textarea 

		if($this->properties) {
           foreach($this->properties as $property => $value) {
           	   $tag->$property = $value;
           }
		}
		$tag->show(); 
		
	}
}