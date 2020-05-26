<?php


namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;

trait EditTrait {
    function onEdit($param) {
    	try {
    		if (isset($param['id'])) {
                $id = $param['id']; // obtém a chave do registro
                Transaction::open($this->connection); // inicia a transação
 
                $class  = $this->activeRecord; // classe Active Record 
                $object = $class::find($id);   // instancia o Active Record
                $this->form->setData($object); // lança os dados no formulário
                Transaction::close();          // finaliza a transação
    		}

    	} catch(Exception $e) {
           new Message('error', $e->getMessage());
           Transaction::rolback();

    	}

    }
}