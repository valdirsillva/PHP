<?php


namespace Livro\Database;

/**
 * @param 14/02/2020
 * @param Design Patterns
 * @param Query Object => É um obj que representa um critério de consulta a base de Dados 
 */

class Criteria {
	private $filters; // Armazena a lista de filtros

	public function __construct() {
		$this->filters = array();
	}

	public function add($variable, $compare_operator, $value, $logic_operator = 'and') {
		// Na primeira linha não precisa concatenar
		if(empty($this->filters)) {
		   $logic_operator = NULL; 
		}
		$this->filters[] = [$variable, $compare_operator, $this->transform($value), $logic_operator];
	}
    /**Faz diversos testes para descobrir o tipo do valor p/ fazer conversão  **/
	private function transform($value) {
       // Se for um array
		if(is_array($value)) {

			foreach($value as $x){

               if(is_integer($x)) {       /** Testa se o valor é inteiro **/
                  $foo[] = $x;
               }
               else if(is_string($x)) {  /** Testa se é string **/
               	 // se for string, adiciona aspas
                  $foo[] = "'$x'";
               }
			}


			/** Converte  o array em string separada por "," **/
			$result = '('.implode(',', $foo) . ')';
			}
			else if(is_string($value)) 
			{
               $result = "'$value'";
			}
			else if(is_null($value)) 
			{
               $result = 'NULL';
			}
			else if(is_bool($value)) 
			{
               $result = $value ? 'TRUE' : 'FALSE';
			}
			else 
			{
			   $result = $value;	
			}
			return $result; // Retorna o valor
		}
	

	public function dump() {
		/** Concatenar a lista de expressões **/
		if(is_array($this->filters) and count($this->filters) > 0) {
           $result = '';
           foreach($this->filters as $filter) 
           {
              $result .= $filter[3] . ' ' . $filter[0] . ' ' . $filter[1] . ' ' . $filter[2] . ' ';
           }
           $result = trim($result);   /** Retira espaço do início e do final de uma string **/
           return "({$result})";
		}
	}

	public function setProperty($property, $value) {
		if(isset($value)) 
		{
           $this->properties[$property] = $value;
		} else {
           $this->properties[$property] = NULL;
		}
	}

	public function  getProperty($property) {
		if(isset($this->properties[$property])) {
		   return $this->properties[$property];	
		}
	}

}