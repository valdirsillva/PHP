<?php


namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;

/**
* 06/04/2020
*
*/

class Combo extends Field implements FormElementInterface 
{
	private $items; /** ** array contendo os itens da combo **/
	protected $properties;

	public function addItems( $items ) 
	{
	   $this->items = $items;	
	}

	public function show() 
	{
		$tag = new Element('select');
		$tag->class = 'combo form-control';
		$tag->name  = $this->name;
		$tag->style = "width:{$this->size}"; /** ** Tam em pixels **/

		/*
		* Cria uma TAG <option> com o valor padrão
		*/
		$option = new Element('option');
		$option->add('');
		$option->value = '0'; // valor da tag

		// add a opc ao combo
		$tag->add($option);
		if($this->items) {
          //percorre os item adicionados
		  foreach($this->items as $chave => $item) {
            // cria uma TAG option 
            $option = new Element('option');
            $option->value = $chave; // define o indice da opção
            $option->add($item);     // add texto da opção
            // se a opção for a selecionada
            if( $chave == $this->value ) {
               // seleciona o item da combo
                $option->selected = 1;
            }
            // adiciona a opção a combo
            $tag->add($option);
		  }	
		}
		// Verifica se o campo é editável 
		if(!parent::getEditable()) {
           // Desabilita tag input
			$tag->readonly = "1";
		}

		if($this->properties) {
            foreach($this->properties as $property => $value ) {
               $tag->$property = $value;
            }
		}
		$tag->show(); // exibe o combo
	}
}