<?php

namespace Livro\Log;

/**
* Data: 06/02/2020
* @author Valdir
* @param => use  design patterns strategy 
*
*/



abstract class Logger {
   protected $filename; # local do  arquivo de LOG

   public function __construct($filename) {
   	  $this->filename = $filename;
   	  file_put_contents($filename, ''); # limpa conteúdo do arquivo
   	  # file_put_contents => Escreve uma string para um arquivo
   }

   # define um método write como obrigatório
   abstract function write($message);

}