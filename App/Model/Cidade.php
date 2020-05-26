<?php

use Livro\Database\Record;

/**
* Data: 26/04/2020
* @param retorna um estado pelo nome
* @param 
*/

class Cidade extends Record 
{
   const TABLENAME = 'tb_cidade';

   private $estado;

   public function get_estado() 
   {  // Se o estado for vazio cria instância de estado e passa o ID
   	  if(empty($this->estado)) {
          $this->estado = new Estado($this->id_estado);
   	  }
   	  return $this->estado;
   }

   public function get_nome_estado() 
   {
   	  if (empty($this->estado)) {
          $this->estado = new Estado($this->id_estado);
   	  }
   	  return $this->estado->nome;   // Retorna nome do estado
   }
}