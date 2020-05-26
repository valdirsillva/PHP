<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\RadioGroup;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Wrapper\FormWrapper;
use Livro\Database\Transaction;
use Livro\Database\Record;

use Livro\Traits\SaveTrait;
use Livro\Traits\EditTrait;


class ProdutosForm extends Page 
{
   private $form, $connection, $activeRecord;

   use SaveTrait;
   use EditTrait;

   public function __construct() 
   {
   	   parent::__construct();

   	   $this->connection = 'dbsistemavendas'; // nome da conexao
   	   $this->activeRecord = 'Produto';       // nome da Active Record

   	   /**  Inst. um formulario **/
   	   $this->form = new FormWrapper( new Form('form_produtos'));
       $this->form->setTitle('Produto');

       /** Cria os campos do formulário  **/
       $codigo      = new Entry('id');
       $descricao   = new Entry('descricao');
       $estoque     = new Entry('estoque');
       $preco_custo = new Entry('preco_custo');
       $preco_venda = new Entry('preco_venda');
       $fabricante  = new Combo('id_fabricante');
       $tipo        = new RadioGroup('id_tipo');
       $unidade     = new Combo('id_unidade');

       /** Carrega os fabricantes do banco de dados **/
       Transaction::open('dbsistemavendas');
       $fabricantes = Fabricante::all();
       $items  = array();
       foreach ($fabricantes as $obj_fabricante) {
          $items[$obj_fabricante->id] = $obj_fabricante->nome;
       }
       $fabricante->addItems($items);

       $tipos = Tipo::all();
       $items = array();
       foreach ($tipos as $obj_tipos) {
          $items[$obj_tipos->id] = $obj_tipos->nome;
       }
       $tipo->addItems($items);

       $unidades = Unidade::all();
       $items = array();

       foreach ($unidades as $obj_unidade) {
           $items[$obj_unidade->id] = $obj_unidade->nome;
       }

       $unidade->addItems($items);
       Transaction::close();

       // define alguns attr p/ os campos do formulário
       $codigo->setEditable(FALSE);
       
       $this->form->addField('Código',           $codigo,      '70%');
       $this->form->addField('Descrição',        $descricao,   '70%');
       $this->form->addField('Estoque',          $estoque,     '70%');
       $this->form->addField('Preço Custo',      $preco_custo, '70%');
       $this->form->addField('Preço Venda',      $preco_venda, '70%');
       $this->form->addField('Fabricante',       $fabricante,  '70%');
       $this->form->addField('Tipo',             $tipo,        '70%');
       $this->form->addField('Unidade',          $unidade,     '70%');
       $this->form->addAction('Salvar', new Action(array($this, 'onSave')));
       
       // add form a pagina 
       parent::add($this->form);

   }
}