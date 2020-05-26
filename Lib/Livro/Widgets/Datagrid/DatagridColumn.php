<?php

namespace Livro\Widgets\Datagrid;
use Livro\Control\Action;


class DatagridColumn 
{
	private $name, $label, $align,  $width, $action, $transformer;

	public function __construct( $name, $label, $align, $width ) 
	{
       /**
       * Atribui os parametros às propriedades dos objetos
       */

       $this->name  = $name;
       $this->label = $label;
       $this->align = $align;
       $this->width = $width;
	}

	public function getName() 
	{
		return $this->name;
	}

	public function getLabel() 
	{
		return $this->label;
	}

	public function getAlign() 
	{
        return $this->align;
	}

	public function getWidth() 
	{
		return $this->width;
	}

	public function setAction(Action $action ) 
	{
		$this->action = $action;
	}

	public function getAction() 
	{
		// verifica se a coluna possui ação
		if($this->action) {
           return $this->action->serialize();
		}
	}

	public function setTransformer($callback) 
	{
        $this->transformer = $callback;
	}

	public function getTransformer() 
	{
		return $this->transformer;
	}


}