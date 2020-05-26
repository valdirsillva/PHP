<?php

use Livro\Control\Page;
use Livro\Control\Action;

use Livro\Session\Session;
use Livro\Database\Transaction;
use Livro\Widgets\Form\Form;
use Livro\Widgets\Form\Entry;
use Livro\Widgets\Form\Combo;
use Livro\Widgets\Form\Text;

use Livro\Widgets\Wrapper\FormWrapper;
/**
** 10/05/2020
**
*/

class ConcluiVendaForm extends Page 
{
    
    private $form;

    public function __construct() 
    {
        parent::__construct();
        new Session; // inst. sessão
        $this->form = new FormWrapper( new Form('form_conclui_venda'));
        $this->form->setTitle('Conclui Venda');

        // Cria os campos do formulário
        $cliente     =  new Entry('id_cliente');
        $valor_venda =  new Entry('valor_venda');
        $desconto    =  new Entry('desconto');
        $acrescimos  =  new Entry('acrescimos');
        $valor_final =  new Entry('valor_final');
        $parcelas    =  new Combo('parcelas');
        $obs         =  new Text('obs');


        $parcelas->addItems(array(1=>'Uma', 2=>'Duas', 3=>'Três'));
        $parcelas->setValue(1);

        // Define uma ação de calculo javascript
        $desconto->onBlur = "$('[name=valor_final])'.val( Number($'[name=valor_venda]').
        val()) + Number($('[name=acrescimos]').val()) - Number($('[name=desconto]')).val()) );";

        $acrescimos->onBlur = $desconto->onBlur;
        $valor_venda->setEditable(FALSE);
        $valor_final->setEditable(FALSE);
        $this->form->addField('Cliente',    $cliente,    '50%');
        $this->form->addField('Valor',      $valor_venda,'50%');
        $this->form->addField('Desconto',   $desconto,   '50%');
        $this->form->addField('Acrescimos', $acrescimos, '50%');
        $this->form->addField('Final',      $valor_final,'50%');
        $this->form->addField('Parcelas',   $parcelas,   '50%');
        $this->form->addField('Obs',        $obs,        '50%');
        $this->form->addAction('Salvar',   new Action(array($this, 'onGravaVenda')));

        parent::add($this->form);  
    }

    public function onLoad($param) 
    {
    	$total = 0;
    	$itens = Session::getValue('list');
    	if ($itens) {
           // Percorre os itens
    		foreach ($itens as $item) {
               $total += $item->preco * $item->quantidade;
    		}
    	}
    	$data = new StdClass;
    	$data->valor_venda = $total;
    	$data->valor_final = $total;
    	$this->form->setData($data);
    }
    /**
    * O método onGravar é executado quando o usuario clicar no botão "Salvar" da tela
    * de conclusão de vendas.
    * Terá como objetivo armazenar a venda e gerar o parcelamento financeiro.
    */
    public function onGravaVenda() 
    {
    	try {
    	   Transaction::open('dbsistemavendas'); // inicia a transação
    	   $dados   = $this->form->getData();      // Obtém os dados da venda
    	   $cliente = Pessoa::find($dados->id_cliente);
    	   if (!$cliente) {
               throw new Exception('Cliente não encontrado');
    	   }
    	   // Verifica se o cliente possui débitos

    	   if ($cliente->totalDebitos() > 0) {
               throw new Exception('Débitos impedem esta operação.');
    	   }
    	   // inicia a gravação da venda
    	   $venda = new Venda;
    	   $venda->cliente     = $cliente;
    	   $venda->data_venda  = date('Y-m-d');
    	   $venda->valor_venda = $dados->valor_venda;
    	   $venda->desconto    = $dados->desconto;
    	   $venda->acrescimos  = $dados->acrescimos;
    	   $venda->valor_final = $dados->valor_final;
    	   $venda->obs         = $dados->obs;

    	   // Lê a variavel list da sessão
    	   $itens = Session::getValue('list');
    	   if ($itens) {
    	   	   // Percorre os itens
    	   	   foreach($itens as $item) {
                  // add o item a venda
    	   	   	  $venda->addItem( new Produto($item->id_produto), $item->quantidade);
    	   	   }
    	   }

    	   // Armazena a venda no banco de dados
    	   $venda->store();
    	   // Gera o financeiro
    	   Conta::geraParcelas($dados->id_cliente, 2, $dados->valor_final, $dados->parcelas);
    	   Transaction::close(); // Finaliza a transação
    	   Session::setValue('list', array()); // limpa a lista de itens da seção

    	   // exibe a mensagem de sucesso
    	   new Message('info', 'Venda registrada com sucesso.');  	

    	} catch(Exception $e) {
           new Message('error', $e->getMessage());
    	}
    }
}