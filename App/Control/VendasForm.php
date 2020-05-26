<?php


use Livro\Control\Page;
use Livro\Control\Action;

use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;

use Livro\Session\Session;

use Livro\Widgets\Container\VBox;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Datagrid\DatagridColumn;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Widgets\Wrapper\DatagridWrapper;
// use Exception;

class VendasForm extends Page 
{
   private $form;
   private $datagrid;
   private $loaded;

   public function __construct() 
   {
   	  parent::__construct();
      new Session();         /** Instancia uma nova sessão **/

      // instancia um formulário
      $this->form   = new FormWrapper( new Form('form_vendas'));
      $this->form->setTitle('Venda');

      // cria os campos do formulário
      $codigo     = new Entry('id_produto');
      $quantidade = new Entry('quantidade');
      $this->form->addField('Código',    $codigo,  '50%');
      $this->form->addField('Quantidade',$quantidade, '50%');
      $this->form->addAction('Adicionar', new Action(array($this, 'onAdiciona')));
      $this->form->addAction('Terminar',  new Action(array(new ConcluiVendaForm, 'onLoad')));

      // instancia objeto Datagrid
      $this->datagrid = new DatagridWrapper( new Datagrid );

      // instancia as colunas da Datagrid
      $codigo     = new DatagridColumn('id_produto',   'Código',    'center', '20%');
      $descricao  = new DatagridColumn('descricao',    'Descrição', 'left',   '40%');
      $quantidade = new DatagridColumn('quantidade',   'Quantidade','left',   '20%');
      $preco      = new DatagridColumn('preco',        'Preço',     'left',   '20%');

      // define um transformador para a coluna preço
      $preco->setTransformer(array($this, 'formata_money'));
      
      // add colunas ao Datagrid
      $this->datagrid->addColumn($codigo);
      $this->datagrid->addColumn($descricao);
      $this->datagrid->addColumn($quantidade);
      $this->datagrid->addColumn($preco);

      $this->datagrid->addAction('Excluir', new Action([$this, 'onDelete']), 'id_produto',
      	'fa fa-trash fa-lg red');

      // Monta a pág atraves de uma caixa
      $box = new VBox;
      $box->style = 'display:block';
      $box->add($this->form);
      $box->add($this->datagrid);

      parent::add($box);

   }


   public function onAdiciona() 
   {
   	   try {
   	   	// obtemos dados do formulario
   	   	$item = $this->form->getData();
   	   	Transaction::open('dbsistemavendas');
   	   	$produto =  Produto::find($item->id_produto); // carrega o produto

   	   	if ($produto) {
            // Busca mais informações do produto
            $item->descricao = $produto->descricao;
            $item->preco     = $produto->preco;

            $list = Session::getValue('list');    // Lê variável $list da sessão
            $list[$item->id_produto] = $item;     // Acrescenta produto na variavel
            Session::setValue('list', $list);     // Grava a variavel de volta à sessão
   	   	}
   	   	Transaction::close('dbsistemavendas'); // fecha transação

   	   } catch(Exception $e) {
          new Message('error', $e->getMessage());
   	   }

   	   $this->onReload(); // Recarrega a listagem
   }

   public function onDelete($param) 
   {
   	    // Lê variavel $list da seção
        $list = Session::getValue('list');
        // Exclui a posição que armazena o produto de codigo
        unset($list[$param['id_produto']]);

        // Grava a variável $list de volta à seção
        Session::setValue('list', $list);
        // recarrega a listagem
        $this->onReload();
   }

   public function onReload() 
   {
   	    // Obtém a variavel de sessão $list
   	    $list = Session::getValue('list');

   	    // Limpa a datagrid
   	    $this->datagrid->clear();
   	    if ($list) {
            foreach ($list as $item) {
               $this->datagrid->addItem($item); // adiciona cada objeto
            }
   	    }
   	    $this->loaded = true;
   }

   /**
   * @return Método de transformação p/ coluna preço  
   *
   */
   public function formata_money($valor) 
   {
       return number_format($valor, 2, ',', '.');
   }

   public function show() 
   {
   	  if (!$this->loaded) {
           $this->onReload();
   	  }
   	  parent::show();
   }
}