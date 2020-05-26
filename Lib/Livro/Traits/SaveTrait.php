<?php

namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Exception;

// 04/05/2020

trait SaveTrait {
	function onSave() {
		try {

			Transaction::open( $this->connection); // open transaction
			$class = $this->activeRecord;          // classe de Active Record
			$dados = $this->form->getData();       // lê os dados do form

			$object = new $class;   // Instância objeto
			$object->fromArray( (array) $dados );  // carrega os dados
			$object->store();  // Armazena o objeto

			Transaction::close();   // finaliza a transação
		    new Message('info', "Dados cadastrados com sucesso.");

		} catch(Exception $e) {
            new Message('error', $e->getMessage());
		}
	}
}