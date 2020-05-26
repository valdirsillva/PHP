<?php

use Livro\Database\Transaction;
/*
* 29/02/2020
*
*/

class PessoaServices 
{
	/**
	* Recebe o código da pessoa e abre uma transação com o Banco de Dados, localizando a pessoa
	* Correspondente pelo método find(), retornando a pessoa na forma de array
	*/
	public static function getData($request) 
	{
        $id_pessoa = $request['id'];
        $pessoa_array = array();
        Transaction::open('dblivrophp'); // Inicia a transação
        $pessoa = Pessoa::find($id_pessoa);
        if($pessoa) {
           $pessoa_array = $pessoa->toArray();
        }
        else {
           throw new Exception("Pessoa {$id_pessoa} nao encontrada");
        }
        Transaction::close(); // Fecha a transação
        return $pessoa_array;
	}
}