<?php

namespace Livro\Widgets\Form;
/**
* Data: 27/03/2020
* @param 
*
*/
interface FormElementInterface 
{
	public function setName($name);
	public function getName();
	public function setValue($value);
	public function getValue();
	public function show();

}