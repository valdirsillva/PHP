<?php



use Livro\Control\Page;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;


class VendasMesChart extends Page 
{
	public function __construct() 
	{
		parent::__construct();
		$loader =  new Twig_Loader_Filesystem('App/Resources');
		$twig   =  new Twig_Environment($loader);
		$template =  $twig->loadTemplate('vendas_mes.html');

		try {
		   // Inicia a transação com o Banco de Dados
           Transaction::open('dbsistemavendas');

           $vendas = Venda::getVendasMes();  // Acessa metodo estático da classe Record
           // var_dump($vendas);
           Transaction::close();             // finaliza a transaçao. 

		} catch(Exception $e) {
           new Message('error', $e->getMessage());
           Transaction::rollback();
		}
        // Vetor de param p/ o templates
		$replaces = array();
		$replaces['title']  = 'Vendas Por Mês';
		$replaces['labels'] = json_encode(array_keys($vendas));
		$replaces['data']   = json_encode(array_values($vendas));

		$content = $template->render($replaces);

		// Cria painel para conter o formulário
		$panel = new Panel('Vendas/Mês');
		$panel->add($content);
		parent::add($panel);
	}
}