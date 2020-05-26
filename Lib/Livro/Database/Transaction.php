<?php

namespace Livro\Database;

use Livro\Log\Logger;

/**
* Data: 02/02/2020
*
*
*
**/

final class Transaction {
	private static $conn;   # attr grava conexão ativa
	private static $logger; # objeto de logger

	private function __construct() {}
   
    # Abre uma conexão com o banco de dados
	public static function open($database) {
		# se for vazio
		if(empty(self::$conn)) {
			self::$conn = Connection::open($database);
			self::$conn->beginTransaction(); # inicia transação
			self::$logger = NULL; # desliga o log SQL
		}
	}
    
    # Retorna a conexão visível
	public static function get() {
		return self::$conn;
	}

	public static function rollback() {
		if(self::$conn) {
		   self::$conn->rollback(); // desfaz as operaçoes realizadas
		   self::$conn = NULL;	
		}
	}

	public static function close() {
		if(self::$conn) {
           self::$conn->commit();
           self::$conn = NULL;
		}
	}

    // Recebe o obj do tipo logger como params
	public static function setLogger(Logger $logger) {
       self::$logger = $logger;
	}

	public static function log($message) {
		if(self::$logger) {
	       self::$logger->write($message); 
		}
	}

    
}