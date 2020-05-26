<?php

use Livro\Control\Page;
use Livro\Control\Action;

use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Database\Transaction;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;

use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;


use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Container\Panel;


/**
* 07/05/2020
*
*/

class ProdutosList extends Page 
{
	private $form;
	private $datagrid;
	private $loaded;
	private $connection;
	private $activeRecord;
	private $filters;

	use DeleteTrait;
	use ReloadTrait {
		onReload as onReloadTrait;
	}

	public function __construct() 
	{
		parent::__construct();
		$this->connection   = 'dbsistemavendas';    // Nome conexão
		$this->activeRecord = 'Produto';            // nome do Active Record

		/**  Instância do formulario **/
		$this->form = new FormWrapper( new Form('form_busca_produtos'));
		$this->form->setTitle('Produtos');

		/**  Cria os campos do formulário **/
		$descricao = new Entry('descricao');
		$this->form->addField('Descrição', $descricao,  '100%');
		$this->form->addAction('Buscar',    new Action(array($this, 'onReload')));
		$this->form->addAction('Cadastrar', new Action(array(new ProdutosForm, 'onEdit')));

        /** Instancia objeto da Datagrid **/
        $this->datagrid = new DatagridWrapper( new Datagrid );
        $codigo      = new DatagridColumn('id',              'Código',    'center',  '10%');
        $descricao   = new DatagridColumn('descricao',       'Descrição', 'left',    '30%');
        $fabrica     = new DatagridColumn('nome_fabricante', 'Fabricante','left',    '30%');
        $estoque     = new DatagridColumn('estoque',         'Estoq.',    'right',   '15%');
        $preco       = new DatagridColumn('preco_venda',     'Venda',     'right',   '15%');

        // add as colunas à datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($descricao);
        $this->datagrid->addColumn($fabrica);
        $this->datagrid->addColumn($estoque);
        $this->datagrid->addColumn($preco);

        $this->datagrid->addAction('Editar', new Action([ new ProdutosForm, 'onEdit']),
        'id', 'fa fa-edit fa-lg  blue');

         $this->datagrid->addAction('Excluir', new Action([$this, 'onDelete']),
        'id', 'fa fa-trash fa-lg  red');


         // monta a pagina através de uma caixa
         $box = new VBox;
         $box->style = 'display:block';
         $box->add($this->form);     // add ao form
         $box->add($this->datagrid); // add ao datagrid

         parent::add($box);

	}

	public function onReload() 
	{
		/** Obém os  dados do formulário de buscas **/
		$dados = $this->form->getData();

		/** Verifica se o usuário preencheu o formulário **/
		if ($dados->descricao) {
            /** Filtra pela descrição  **/
            $this->filters[] = ['descricao', 'like', "%{$dados->descricao}%", 'and'];
		} 
		$this->onReloadTrait();
		$this->loaded = true;
	}
	/**
	* Método Show() sobrescrito
	* Para garantir que o método onReload() sempre seja executado antes de a pagina ser carregada
	*/
	public function show() 
	{
		// Se a listagem não foi carregada
		if(!$this->loaded) {
            $this->onReload();
		}
		parent::show();
	}

}
