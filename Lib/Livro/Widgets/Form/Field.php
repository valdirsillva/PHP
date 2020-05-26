<?php

namespace Livro\Widgets\Form;
use Livro\Widgets\Base\Element;
/**
* Data: 29/03/2020
* Valdir Silva
*
*
*
**/


abstract  class Field implements FormElementInterface 
{
	protected $name;
	protected $size;
	protected $value;
	protected $editable;
	protected $tag;
	protected $formLabel;
	protected $properties;

	/**
	* @param construct
	* Define algumas caracteriscicas iniciais
	*/
	public function __construct( $name ) 
	{
       self::setEditable(true);
       self::setName($name);
	}

	/**
	* Seta uma nova propriedade
	*
	**/
	public function setProperty($name, $value) 
	{
        $this->properties[$name] =  $value;
	}

	/** @return retorna uma propriedade 
	*
	*
	**/
	public function getProperty($name) 
	{
		return $this->properties[$name];
	}

	/**
	* Os métodos mágicos serão interceptadores de atribuições e leituras de propriedades
	* @return prop
	*
	*/
	public function __set( $name, $value ) 
	{  /** ** attr scalares : int, boolean , string **/
		if(is_scalar($value)) {

		}
	}
	public function __get( $name) 
	{
        return $this->getProperty($name);
	}
	/**
	* @param setName  - Define o nome do campo
	* @return getName - Retorna o nome do campo
	**/

	public function setName($name) 
	{
		$this->name = $name;
	}

	public function getName() 
	{
		return $this->name;
	}
	/**
	* @param setLabel - define o rótulo de texto (label)
	*
    */

    public function setLabel($label) 
    {
    	$this->formLabel = $label; 
    }

    public function getLabel() 
    {
    	return $this->formLabel;
    }

    public function setValue( $value ) 
    {
        $this->value = $value;
    }

    public function getValue() 
    {
        return $this->value;
    }
    /**
    *
    *
    */

    public function setEditable($editable) 
    {
    	$this->editable = $editable;
    }

    public function getEditable() 
    {
    	return $this->editable;
    }

    /**
    * Define o tamanho do campo
    *
    */

    public function setSize($width,$height = NULL) 
    {
    	$this->size = $width;
    }


}


