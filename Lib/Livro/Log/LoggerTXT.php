<?php

namespace Livro\Log;
/**
* Data: 06/02/2020
* @author Valdir
* @param => use  design patterns strategy 
*
*/



class LoggerTXT extends Logger {
   // Implementa operaççao da classe abstrata Logger
   public function write($message) {
   	 date_default_timezone_get('America/Sao_Paulo');
   	 $time = date("Y-m-d H:i:s");

   	 #  Monta string
   	 $text = "$time :: $message\n";

   	 # add ao final do arquivo
   	 $handle = fopen($this->filename, 'a');
   	 fwrite($handle, $text);
   	 fclose($handle);
   }   
}