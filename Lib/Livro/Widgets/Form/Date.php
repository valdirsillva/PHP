<?php

namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;

/**
 * classe Date
 * classe para construção de caixas de texto
 */


class Date extends Entry implements FormElementInterface 
{
    /**
    * Exibe o Widget na tela 
    *
    */

    public function  show() 
    {
    	$tag = new Element('input');
    	$tag->class = 'field form-control';
    	$tag->name  = $this->name;
    	$tag->value = $this->value;
    	$tag->type  = 'date';
    	$tag->style = "width:{$this->size}"; // tamanho em pixels
        
        // Verifica se o campo não é editável
        if (!parent::getEditable()) {
            $tag->readonly = '1';
        }

        if ($this->properties) {
            foreach($this->properties as $property => $value) {
               $tag->$property = $value;
            }
        }
        // exibe a tag
        $tag->show();
    }
}