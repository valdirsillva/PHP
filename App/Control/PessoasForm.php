<?php

use Livro\Control\Page;
use Livro\Control\Action;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\CheckGroup;
use Livro\Database\Transaction;
use Livro\Widgets\Container\Panel;
use Livro\Widgets\Wrapper\FormWrapper;




class PessoasForm extends Page 
{
	private $form;


	public function __construct() 
	{
		parent::__construct();
		// instancia o formulario
		$this->form = new FormWrapper( new Form('form_pessoas') );
		$this->form->setTitle('Pessoa');

		// Cria os campos do formulário
		$codigo      = new Entry('id');
        $nome        = new Entry('nome');
        $endereco    = new Entry('endereco');
        $bairro      = new Entry('bairro');
        $telefone    = new Entry('telefone');
        $email       = new Entry('email');
        $cidade      = new Combo('id_cidade');
        $grupo       = new CheckGroup('ids_grupos');
        $grupo->setLayout('horizontal');

        // Carrega as cidades do Banco de Dados
        Transaction::open('dbsistemavendas');
        $cidades = Cidade::all();
        $items = array();
        foreach($cidades as $obj_cidade) {
           $items[$obj_cidade->id] = $obj_cidade->nome;
        }
        $cidade->addItems($items);
        
        $grupos = Grupo::all();
        $items  = array();
        foreach($grupos as $obj_grupo) {
            $items[$obj_grupo->id] = $obj_grupo->nome;
        } 
        $grupo->addItems($items);
        Transaction::close();

        $this->form->addField('Código',   $codigo,   '70%');
        $this->form->addField('Nome',     $nome,     '70%');
        $this->form->addField('Endereço', $endereco, '70%');
        $this->form->addField('Bairro',   $bairro,   '70%');
        $this->form->addField('Telefone', $telefone, '70%');
        $this->form->addField('Email',    $email,    '70%');
        $this->form->addField('Cidade',   $cidade,   '70%');
        $this->form->addField('Grupo',    $grupo,    '70%');

        // define alguns atributos para os campos do formulário
        $codigo->setEditable(FALSE);
        $this->form->addAction('Salvar', new Action(array($this, 'onSave')));

        // add  o formulario a pagina
        parent::add($this->form);
	}

	public function onSave() 
	{
		try {
			// Abre uma nova transação com o BD
			Transaction::open('dbsistemavendas');
			$dados = $this->form->getData();
			$this->form->setData($dados);

			$pessoa = new Pessoa; // inst obj
			$pessoa->fromArray( (array) $dados ); // carrega os dados
			$pessoa->store();      // Armazena o obj no banco de dados
			$pessoa->delGrupos();

			if ($dados->ids_grupos) {
                foreach ($dados->ids_grupos as $id_grupo) {
                   $pessoa->addGrupo( new Grupo($id_grupo));
                }
			}

			Transaction::close(); // finaliza a transação
			new Message('info', 'Dados armazenados com sucesso !');

		} catch(Exception $e) {
            // Exibe a mensagem de Exceção
			new Message('error', $e->getMessage());
			// Desfaz todas as alterações no banco de dados
			Transaction::rolback();
		}
	}
    public function onEdit($param) 
    {
        try {

            if(isset($param['id'])) {
                $id = $param['id']; // obtém a chave
                Transaction::open('dbsistemavendas');
                $pessoa = Pessoa::find($id);
                if ($pessoa) {
                    $pessoa->ids_grupos = $pessoa->getIdsGrupos();
                    $this->form->setData($pessoa); // lança os dados da pessoa no formulário
                }
                Transaction::close(); // finaliza a transação

            }

        } catch(Exception $e) {
            new Message('error', $e->getMessage()); // exibe msg gerada pela exceção
            Transaction::rolback(); // desfaz todas as alterações no banco de dados
        }
    }
} 