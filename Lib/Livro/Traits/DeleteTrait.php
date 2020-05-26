<?php

namespace Livro\Traits;

use Livro\Control\Action;
use Livro\Database\Transaction;
use Livro\Widgets\Dialog\Message;
use Livro\Widgets\Dialog\Question;
use Exception;

// 05/05/2020

trait DeleteTrait 
{
    function onDelete( $param ) 
    {
       $id = isset($param['id']) ? $param['id'] : NULL; // recebe o parametro id
       $action1 = new Action(array($this, 'Delete'));   // cria a ação
       $action1->setParameter('id', $id);
       new Question('Deseja realmente exluir o registrio ?', $action1);
    }

    function Delete($param) 
    {
    	try {
    	  $id = isset($param['id']) ? $param['id']	: NULL;
    	  Transaction::open( $this->connection );    // inicia a transação

    	  $class  = $this->activeRecord;   // classe Active Record;
    	  $object = $class::find($id);     // instancia o obj
    	  $object->delete();               // delete obj do banco de dados
    	  Transaction::close();            // Finaliza a transação
    	  $this->onReload();               // recarrega o datagrid

    	  new Message('info', "Registro exluído com sucesso.");


    	} catch(Exception $e) {
          new Message('error', $e->getMessage());
    	}
    }
}