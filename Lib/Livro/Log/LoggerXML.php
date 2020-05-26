<?php

namespace Livro\Log;
/**
* Data: 06/02/2020
* @author Valdir
* @param => use  design patterns strategy 
*
*/



class LoggerXML extends Logger {
   // Implementa operaççao da classe abstrata Logger
   public function write($message) {
   	 date_default_timezone_get('America/Sao_Paulo');
   	 $time = date("Y-m-d H:i:s");

   	 $text  = " <log>\n";
   	 $text .= " <time>$time</time>\n";
   	 $text .= " <message>$message</message>\n";
   	 $text .= "</log>\n";

   	 #adiciona ao final do arrquivo
   	 $handle  = fopen($this->filename, 'a');
   	 fwrite($handle, $text);
   	 fclose($handle);
   }   
}