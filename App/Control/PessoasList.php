<?php


use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Button;
use Livro\Widgets\Form\Field;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Container\VBox;
use Livro\Widgets\Dialog\Question;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Wrapper\DatagridWrapper;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Database\Criteria;
use Livro\Database\Repository;
use Livro\Database\Transaction;

/**
* 03/05/2020
*
*/

class PessoasList extends Page 
{
    private $form;     // formulário de buscas
    private $datagrid; // datagrid
    private $loaded;   // listagem

    public function __construct() 
    {
    	parent::__construct();

    	// Instancia do formulário
    	$this->form = new FormWrapper( new  Form('form_busca_pessoas'));
    	$this->form->setTitle('Pessoas');
    	$nome = new Entry('nome');
    	$this->form->addField('Nome', $nome, '100%');
    	$this->form->addAction('Buscar', new Action(array($this, 'onReload')));
    	$this->form->addAction('Novo',   new Action(array( new PessoasForm, 'onEdit')));

    	// Instancia obj Datagrid
    	$this->datagrid = new DatagridWrapper( new Datagrid );

    	// Instancia as colunas do datagrid
    	$codigo      = new DatagridColumn('id',       'Código',  'center', '10%');
    	$nome        = new DatagridColumn('nome',     'Nome',    'left',   '40%');
    	$endereco    = new DatagridColumn('endereco', 'Endereço','left',   '30%');
    	$cidade      = new DatagridColumn('nome_cidade',   'Cidade',  'left',   '20%');

        // add as colunas ao datagrid
        $this->datagrid->addColumn($codigo);
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($endereco);
        $this->datagrid->addColumn($cidade);

        $this->datagrid->addAction('Editar', new Action([new PessoasForm, 'onEdit']),
        'id', 'fa fa-edit fa-lg blue');
        $this->datagrid->addAction('Excluir', new Action([$this, 'onDelete']),
        'id', 'fa fa-trash fa-lg red');
        
        // Monta a página por meio de uma caixa
        $box = new VBox;
        $box->style = 'display:block';
        $box->add($this->form);
        $box->add($this->datagrid);

        parent::add($box);
    }

    public function onReload() 
    {
    	Transaction::open('dbsistemavendas');   // inicia a transação
    	$repository = new Repository('Pessoa'); // inst o Repository
    	// Cria um critério de seleção
    	$criteria = new Criteria;
    	$criteria->setProperty('order', 'id');
    	// obtém os dados do formulário de busca
    	$dados = $this->form->getData();

    	// Verifica se o usuário preencheu o form
    	if ($dados->nome) {
           // Filtra pelo nome da pessoa
    	   $criteria->add('nome', 'like', "%{$dados->nome}%");
    	} 

    	// Carrega os dados que satisfazem o critério
    	$pessoas = $repository->load($criteria);
    	$this->datagrid->clear();
    	if ($pessoas) {
            foreach($pessoas as $pessoa) {
               // adiciona o obj ao datagrid
               $this->datagrid->addItem($pessoa);
            }
    	}
    	// Finaliza a transação
    	Transaction::close();
    	$this->loaded = true;
    }

    public function onDelete($param) 
    {
    	$id = isset($param['id']) ? $param['id'] : NULL;
    	$action1 = new Action(array($this, 'Delete'));
    	$action1->setParameter('id', $id);

    	new Question('Deseja realmente excluir o registro ?', $action1);
    }

    public function Delete($param) 
    {
    	try{
    		$id = isset($param['id']) ? $param['id'] : NULL;
    		Transaction::open('dbsistemavendas');
    		$pessoa = Pessoa::find($id);
    		$pessoa->delete();           // Deleta pessoa do banco de dados
    		Transaction::close();        // finaliza  a transaçao
    		$this->onReload();           // Recarrega o datagrid
    		new Message('info', "Registro Excluído com sucesso");

    	} catch(Exception $e) {
            new Message('error', $e->getMessage());
    	}
    }

    public function show() 
    {
    	// Se a listagem não foi carregada
    	if(!$this->loaded) {
           $this->onReload();
    	}
    	parent::show();
    }

}