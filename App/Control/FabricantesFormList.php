<?php

use Livro\Control\Page;
use Livro\Control\Action;

use Livro\Database\Transaction;

use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Wrapper\DatagridWrapper;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;
use Livro\Traits\DeleteTrait;
use Livro\Traits\ReloadTrait;

// Classe teste : 09/05/2020
class FabricantesFormList extends Page {
	private $form, $datagrid, $loaded, $connection, $activeRecord;
    use EditTrait;
    use DeleteTrait;
    use ReloadTrait {
    	onReload as onReloadTrait;
    }
    use SaveTrait {
    	onSave as onSaveTrait;
    }

    /*
     * método construtor
     * Cria a página, o formulário e a listagem
     */
	public function __construct() {
		parent::__construct();

		$this->connection = 'dbsistemavendas';
		$this->activeRecord = 'Fabricante';

		// Instância um formulário
		$this->form = new FormWrapper( new Form('form_fabricante'));
		$this->form->setTitle('Fabricante');

		// Cria os campos do formulário
		$codigo    = new Entry('id');
		$nome      = new Entry('nome');
		$site      = new Entry('site');
		$codigo->setEditable(FALSE);

		$this->form->addField('Código',  $codigo,   '70%');
		$this->form->addField('Nome',    $nome,    '70%');
		$this->form->addField('Site',    $site,   '70%');

		// add ações pag
		$this->form->addAction('Salvar', new Action(array($this, 'onSave')));
		$this->form->addAction('Limpar',  new Action(array($this, 'onEdit')));

		// instância a Datagrid
		$this->datagrid = new DatagridWrapper( new Datagrid );

		// Instância as colunas do datagrid
		$codigo  =  new DatagridColumn('id',         'Código', 'center',    '10%');
		$nome    =  new DatagridColumn('nome',       'Nome',   'left',      '50%');
		$estado  =  new DatagridColumn('site' ,      'Site',   'Site',    '40%');

		// add as colunas à Datagrid 
		$this->datagrid->addColumn($codigo);
		$this->datagrid->addColumn($nome);
		$this->datagrid->addColumn($estado);

		$this->datagrid->addAction('Editar', new Action([$this, 'onEdit']), 'id',
	    'fa fa-edit fa-lg blue');

	    $this->datagrid->addAction('Excluir', new Action([$this, 'onDelete']), 'id',
	    'fa fa-trash fa-lg, red');

        /** Monta  a pág de uma tabela **/
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($this->form);
        $box->add($this->datagrid);

        parent::add($box);

	}

	/**
	** onSave será executado sempre que o  usuário clicar no botão salvar
	* é executado o method padrão de gravação de dados vindo do SaveTrait
	*/

	public function onSave() 
	{
        $this->onSaveTrait();
        $this->onReload();
	}
	/**
	* @return o metodo onReload é executado sempre que a pagina for carregada e logo após 
	* a gravação dos dados.
	* Executa o metodo padrão de carregamento vindo do ReloadTrait
	*/
	public function onReload() 
	{
		$this->onReloadTrait();
		$this->loaded = true;
	}
	/**
	* @return o método show() é sobrescrito p/ garantir que o método onReload
	* sempre seja executado antes de a página ser carregada.
	*/
	public function show() 
	{
	  // Se a pág ainda não foi carregada 
	  if (!$this->loaded) {
          $this->onReload();
	  }	
	  parent::show();
	}



	
}