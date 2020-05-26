<?php


use Livro\Control\Page;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;


class PessoasReport extends Page 
{
	public function __construct() 
	{
		parent::__construct();

		$loader = new Twig_Loader_Filesystem('App/Resources');
		$twig   = new Twig_Environment($loader);
		$template = $twig->loadTemplate('pessoas_report.html');

		// Vetor de param p/ o template
		$replaces = array();

		try {
		   Transaction::open('dbsistemavendas');
		   $replaces['pessoas'] = ViewSaldoPessoa::all();
		   Transaction::close(); // fecha a transaçao	

		} catch(Exception $e) {
           new Message('error', $e->getMessage());
           Transaction::rollback();
		}

		$content = $template->render($replaces);

		// Cria um painel p/ conter o formulário
		$panel = new Panel('Pessoas');
		$panel->add($content);      // add o conteudo ao painel
		parent::add($panel);

	}

}