<?php

namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;
/**
* Data: 07/04/2020
*/

class CheckGroup extends Field implements FormElementInterface 
{
	private $layout = 'vertical';
	private $items;
    
    /**
    * @param indica se os botões estão um ao lado do outro 
    *
    */
	public function setLayout($dir) 
	{
		$this->layout = $dir;
	}

    /**
    * @param recebe um conjunto de opções e armazena na prop items
    *
    */
	public function addItems($items) 
	{
		$this->items = $items;
	}

	/**
	* @param exibe um conjunto de checkbutton
	*
	*/

	public function show() 
	{
		if($this->items) {
           /** ** Percorre cada uma das opções do radio **/
           foreach( $this->items as $index => $label ) {
              $button = new CheckButton("{$this->name}[]");
              $button->setValue($index);

              /** Verifica se deve ser marcado **/
              if(in_array($index, (array) $this->value )) {
                 $button->setProperty('checked','1');
              }
              $obj = new Label($label);
              $obj->add($button);
              $obj->show();
              if($this->layout == 'vertical') {
                 // Exibe uma TAG de quebra de linha
              	 $br = new Element('br');
              	 $br->show();
              	 print "\n";
              }
           }
		}
	}
}