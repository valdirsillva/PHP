<?php

use Livro\Control\Page;
use Livro\Widgets\Container\Panel;
/**
* Exibe o código fonte
*/

class ViewSource extends Page 
{
	private $form;

	public function onView($param) 
	{
		$class = $param['source'];
		$file  = "App/Control/{$class}.php";
		if (file_exists($file)) {
            $panel = new Panel(	'Código-fonte:'. $class);
            $panel->add( highlight_file($file, TRUE));
            /** highlight_file() => mostra uma versão do código contido em filename com a sintaxe destacada usando as cores definidas **/
            parent::add($panel);
		}
	}
}
