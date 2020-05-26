<?php

namespace Livro\Database;

/**
* @param Repository Pattern => É uma camada na aplicação que trata de mediar a comunicaçao por meio de uma coleção
*        de objetos de negócios e o banco de dados. Uma classe Repository deve aceitar critérios  que permitam selecionar
*        um determinado grupo de objetos de forma flexível. 
*
* Data: 15/02/2020
*
*/


final class Repository {
    private $activeRecord; /** Classe manipulada pelo Repositório **/

    function __construct($class) {
    	$this->activeRecord = $class;
    }

    /** Reponsável por carregar uma coleção de obj  recebendo um critério de seleção e construirá uma query **/

    function load(Criteria $criteria) {
    	/** Inst instrução de SELECT **/
    	$sql = "SELECT * FROM ". constant($this->activeRecord.'::TABLENAME');
    	
    	/**Obtém a cláusula WHERE do objeto critéria **/
        if($criteria) {
        	$expression = $criteria->dump();
        	if($expression) {
        	   $sql .= ' WHERE '.$expression;
        	}
        	/** Obtém as propriedades do Critério  **/
        	$order  = $criteria->getProperty('order');
        	$limit  = $criteria->getProperty('limit');
        	$offset = $criteria->getProperty('offset');

        	/** obtém ordenação do SELECT  **/
        	if($order) {
        		$sql .= ' ORDER BY '. $order;
        	}
        	if($limit) {
                $sql .= ' LIMIT ' . $limit;
        	}
        	if($offset) {
                $sql .= ' OFFSET ' .$offset;
        	}
        }

        /** obtém transação ativa **/
        if($conn = Transaction::get()) {
           Transaction::log($sql); /** registro mensagem **/

           /** executa a consulta no banco dados  **/
           $result  = $conn->query($sql);
           $results = array();

           if($result) {
              /** Percorre os resultado da consulta, retornando um objeto  **/
              while($row = $result->fetchObject($this->activeRecord)) {
                /** Armazena no array $results **/
                $results[] = $row;
              }
           }
           return $results; 
        }
        else {
        	throw new Exception('Não há transação ativa !');
        	
        }

    }

    function delete(Criteria $criteria) {
    	$expression = $criteria->dump();
     	$sql = " DELETE FROM " . constant($this->activeRecord.'::TABLENAME');
        if($expression) {
           $sql .= ' WHERE '. $expression;	
        }
        /** Obtém transação ativa **/

        if($conn = Transaction::get()) {
           Transaction::log($sql);      /* Registra mensagem de log */
           $result = $conn->exec($sql); /* Executa instrução DELETE */
           return $result;
        }
        else {
        	throw new Exception('Não há transação ativa !');
        }

    }

    /* Conta quantos obj satisfazem a um dado critério */
    function count(Criteria $criteria) {
        $expression = $criteria->dump();
        $sql = "SELECT count(*) FROM ". constant($this->activeRecord.'::TABLENAME');
        if($expression) {
           $sql .= ' WHERE ' . $expression;	
        }

        /* Obtém transação ativa */
        if($conn = Transaction::get()) {
           Transaction::log($sql);
           $result  = $conn->query($sql);
           if($result) {
           	  $row = $result->fetch();
           }
           return $row[0]; /* Retorna o resultado */	
        }
        else {
        	throw new Exception('Não há transação ativa !');
        }   

    }
    
}


