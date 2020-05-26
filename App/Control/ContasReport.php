<?php

use Livro\Control\Page;
use Livro\Control\Action;

use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Date;
use Livro\Widgets\Dialog\Message;
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;


use Dompdf\Dompdf;
use Dompdf\Options;

/**
* Data: 12/05/2020
* @author Valdir
*/

class ContasReport extends Page 
{
    private $form; // Formulário de entrada

    public function __construct() 
    {
    	parent::__construct();

    	/** Instancia um formulario **/
    	$this->form = new FormWrapper( new Form('form_relat_contas'));
    	$this->form->setTitle('Relatório de Contas');

    	/** Cria os campos do formulário  **/
    	$data_ini = new Date('data_ini');
    	$data_fim = new Date('data_fim');

    	$this->form->addField('Vencimento Inicial', $data_ini, '50%');
    	$this->form->addField('Vencimento Final',   $data_fim, '50%');
    	$this->form->addAction('Gerar', new Action(array($this, 'onGera')));
    	$this->form->addAction('PDF',    new Action(array($this, 'onGeraPDF')));
    	parent::add($this->form);
    }


    public function onGera() 
    {
    	$loader = new Twig_Loader_Filesystem('App/Resources');
    	$twig   = new Twig_Environment($loader);
    	$template = $twig->loadTemplate('contas_report.html');

    	/** Obtém os dados do formulário **/
    	$dados = $this->form->getData();

    	// Joga os dados de volta ao formulario
    	$this->form->setData($dados);

    	/**  Lê os campos do formulario, converte para padrão americano **/
    	$data_ini = $dados->data_ini;
    	$data_fim = $dados->data_fim;

    	/** Vetor de parâmetros p/ o template **/
    	$replaces = array();
    	$replaces['data_ini'] = $dados->data_ini;
    	$replaces['data_fim'] = $dados->data_fim;

    	try {
    		/** Inicia uma transação com o Banco de Dados */
    		Transaction::open('dbsistemavendas');

    		// instancia um repositório da classe Conta
    		$repositorio = new Repository('Conta');

    		// Cria um criterio de seleção
    		$criterio = new Criteria;
    		$criterio->setProperty('order','dt_vencimento');

    		if($dados->data_ini) {
               $criterio->add('dt_vencimento', '>=', $data_ini);
    		}

    		if($dados->data_fim) {
               $criterio->add('dt_vencimento', '<=', $data_fim);
    		}
    		// Lê contas que satisfazem os critérios
    		$contas = $repositorio->load($criterio);
            
            if ($contas) {
                foreach($contas as $conta) {
                  $conta_array = $conta->toArray();
                  $conta_array['nome_cliente'] = $conta->cliente->nome;
                  $replaces['contas'][]  = $conta_array;
                }
            }
            // Finaliza a transação
            Transaction::close();

    	} catch(Exception $e) {
           new Message('error',  $e->getMessage());
           Transaction::rollback();
    	}

    	$content = $template->render($replaces);
    	$title = 'Contas';
    	$title.= (!empty($dados->data_ini)) ? ' de ' . $dados->data_ini : '';
    	$title.= (!empty($dados->data_fim)) ? ' de ' . $dados->data_fim : '';

    	// cria o painel p/ conter o formulario
    	$panel = new Panel($title);
    	$panel->add($content);
    	parent::add($panel);
    	return $content;

    }

    public function onGeraPDF($param) 
    {
    	$html = $this->onGera($param); // gera o relatorio em HTML Primeiro

    	$options = new Options();
    	$options->set('dpi', '128');

    	// DomPDF converte o HTML  em PDF
    	$dompdf = new Dompdf($options);
    	$dompdf->loadHtml($html);
    	$dompdf->setPaper('A4', 'portrait');
    	$dompdf->render();
    	// escreve o arquivo e abre em tela
    
    	$filename = 'tmp/contas.pdf';
    	if (is_writable('tmp')) { // se o arquivo pode ser modificado
           file_put_contents($filename, $dompdf->output());
           echo "<script>window.open('{$filename}')</script>";
    	}
    	else {
    		new Message('error', 'Permissão negada em: '.$filename);
    	}
    }
}