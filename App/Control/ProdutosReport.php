<?php

use Livro\Control\Page;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;


/*
* 12/05/2020
* classe p/ relatório de vendas e qrCode
*/

class ProdutosReport extends Page 
{
	/**
	* Método construtor
	*/
	public function __construct() 
	{
		parent::__construct();

		$loader = new Twig_Loader_Filesystem('App/Resources'); // indica o diretório dos templates
		$twig = new Twig_Environment($loader);                 // cria instância p/ manipular o template
		$template = $twig->loadTemplate('produtos_report.html'); // lê o template

		$replaces = array(); // Cria um vetor de parâmetros p/ o template

		// gerador  Barcode em HTML
		$generator = new Picqer\Barcode\BarcodeGeneratorHTML();

		//gerador qrCode em SVG
		$renderer = new  \BaconQrCode\Renderer\Image\Svg();
		$renderer->setHeight(256);
		$renderer->setWidth(256);
		$renderer->setMargin(0);

		$write = new \BaconQrCode\Writer($renderer);


		try {
			// inicia transação com o banco de dados
			Transaction::open('dbsistemavendas');

			$produtos = Produto::all();
			foreach ($produtos as $produto) {
               $produto->barcode = $generator->getBarcode($produto->id, $generator::TYPE_CODE_128, 5, 100);
               $produto->qrcode  = $write->writeString($produto->id . ' ' . $produto->descricao);
			}

			$replaces['produtos'] = $produtos;
			Transaction::close(); // Fecha a transação

		} catch(Exception $e) {
            new Message('error', $e->getMessage());
            Transaction::close();
		}

		$content = $template->render($replaces);

		// cria um painel p/ conter o formulario
		$panel = new Panel('Produtos');
		$panel->add($content);
		parent::add($panel);


	}
}