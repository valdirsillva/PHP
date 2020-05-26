<?php

namespace Livro\Database;
use PDO;
use Exception;

/**
* 24/02/2020
*
*/


final class Connection {
    /** Design Factory  Method **/
	private function __construct() {}

	public static function open($name) {
		# Verifica se existe arquivo de configuração para este banco de dados
		if(file_exists("App/Config/{$name}.ini")) {
			$db = parse_ini_file("App/Config/{$name}.ini");
		} else {
			throw new Exception("Arquivo '$name' não encontrado. ");
		}

		# lê as informações no arquivo .ini

		$user = isset($db['user']) ? $db['user'] : NULL;
		$pass = isset($db['pass']) ? $db['pass'] : NULL;
		$name = isset($db['name']) ? $db['name'] : NULL;
		$host = isset($db['host']) ? $db['host'] : NULL;
		$type = isset($db['type']) ? $db['type'] : NULL;
		$port = isset($db['port']) ? $db['port'] : NULL;

		# Identifica qual o tipo (driver) do BD a ser utilizado
		switch ($type) {
			case 'pgsql':
				$port = $port ? $port : '5432';
				$conn = new PDO("pgsql:dbname={$name}; user={$user}; password={$pass}; host=$host; port={$port}");
				break;
			case 'mysql':
			    $port = $port ? $port : '3306';
			    $conn = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8", $user, $pass);
			    break;
			case 'sqlite':
			    $conn = new PDO("sqlite:{$name}");
			    $conn->query('PRAGMA foreign_keys = ON');
			    break;
			case 'ibase':
			    $conn = new PDO("firebird:dbname={$name}", $user, $pass);
			    break;
			case 'oci8':
			    $conn = new PDO("oci:dbname={$name}", $user, $pass);
			    break;
			case 'msql':
			    $conn = new PDO("dblib:host={$host}, 1433;dbname={$name}", $user, $pass);
			    break;
		}
		# PDO lança exceção, ocorrência de erros

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}
}


