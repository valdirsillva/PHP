<?php

use Livro\Database\Record;
use Livro\Database\Criteria;
use Livro\Database\Repository;

/**
*
** Data: 01/05/2020
** Valdir Silva
** Conta => representará  a tabela conta, e é subclasse de record 
** 
*/

class  Conta extends  Record  
{
    const TABLENAME = 'tb_conta';
    private $cliente;
    
    /**
    * Exec automaticamente qnd o desenvolvedor acessar o attr $conta->cliente
    ** @return objeto da classe pessoa
    **
    **
    */

    public function get_cliente() 
    {
    	if(empty($this->cliente)) {
           $this->cliente = new Pessoa($this->id_cliente);
    	}
        return $this->cliente;
    }

    /**
    * @return todos os obj com status paga<> 'S' (não pagas)
    */
    public static  function getByPessoa( $id_pessoa ) 
    {
    	$criteria = new Criteria;
    	$criteria->add('paga', '<>', 'S');
    	$criteria->add('id_cliente', '=', $id_pessoa);
        
        // Inst. o repositorio de  conta 
    	$repository = new Repository('Conta');
    	return $repository->load($criteria);
    }

    /**
    * @return valor Total das contas em aberto de uma pessoa
    *
    */
    public static function debitosPorPessoa($id_pessoa) 
    {
        $total = 0;
        $contas = self::getByPessoa($id_pessoa);
        if ($contas) {
        	foreach($contas as $conta) {
                $total += $conta->valor;
        	}
        }
        return $tota;
    }

    public static function geraParcelas($id_cliente, $delay, $valor, $parcelas)
    {
    	$date = new DateTime(date('Y-m-d'));
    	$date->add(new DateInterval('P'.$delay.'D'));
    	for($n=1; $n<=$parcelas; $n++) {
            $conta = new self;
            $conta->id_cliente = $id_cliente;
            $conta->dt_emissao = date('Y-m-d');
            $conta->dt_vencimento = $date->format('Y-m-d');
            $conta->valor = $valor / $parcelas;
            $conta->paga = 'N';
            $conta->store();
            $date->add(new DateInterval('P1M'));
    	}
    } 
}