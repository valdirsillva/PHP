<?php


namespace Livro\Database;
use Exception;

/**
* Data: 24/02/2020
* @author Valdir Silva
* Design Patterns => Layer Supertype 
*
*/


abstract class Record implements RecordInterface {
   protected $data; # Array contendo os dados do objeto

   public function __construct($id = NULL) {
   	 # Se o id for informado
   	 if($id) 
     {
        # carrega o obj correspondente
        $object = $this->load($id);

        if($object) 
        {
           $this->fromArray($object->toArray());	
        }
   	 }
   }

   public function __clone() {
   	unset($this->data['id']);
   }

   public function __set($prop, $value) {
   	 if(method_exists($this, 'set_'.$prop)) {
       # Executa  o método set_ <propriedade>
   	   call_user_func(array($this, 'set_'.$prop),  $value);	
   	 } 
     else {

   	 	 if($value === NULL ) 
       {
           unset($this->data[$prop]);
   	 	 } 
   	 	 else {
          $this->data[$prop] = $value; # atribui o valor da propriedade
   	 	 }
   	 } 
   }

   public function __get($prop) {
   	 if(method_exists($this, 'get_'.$prop)) {
   	   # executa método get_<propriedade>
   	   return call_user_func(array($this, 'get_'.$prop));	
   	 } else {
   	 	if(isset($this->data[$prop])) {
   	 	   return $this->data[$prop]; 	
   	 	}
   	 }
   }
   
   # Será executado automaticamente sempre que for testado a presença de um valor no obj
   public function __isset($prop) {
   	 return isset($this->data[$prop]);
   }

   # Responsável por retornar o nome  da tabela na qual a Active Record será persistido.
   private function getEntity() {
   	 $class = get_class($this); # obtém o nome da classe
   	 return constant("{$class}::TABLENAME");    // retorna a constante de classe TABLENAME
   }

   # Preenche atributos de um Active Record com os dados de um array
   public function fromArray($data) {
   	  $this->data = $data;
   }

   # Retorna todos os atributos de um obj armazenando em data
   public function toArray() {
   	 return $this->data;
   }

   public function store() {
     $prepared = $this->prepare($this->data); # recebe um array de dados

   	 # Verifica se tem ID ou se existe na base de dadcos
   	 if(empty($this->data['id']) or (!$this->load($this->id))) {
   	 	
      # incrementa o ID
   	 	if(empty($this->data['id'])) {
   	 	   $this->id = $this->getLast() +1;
   	 	   $prepared['id'] = $this->id; 	
   	 	}
   	 	# cria uma instrução de insert 
   	 	  $sql = "INSERT INTO {$this->getEntity()}".
   	 	       '('. implode(', ', array_keys($prepared)) . ')'.
   	 	       ' VALUES '.
   	 	       '('. implode(', ', array_values($prepared)) . ')'; 
   	 } 
     else {
   	 	# Monta a string de UPDATE
   	 	$sql = " UPDATE {$this->getEntity()}";
   	 	// Monta os pares: coluna=valor,...
   	 	if($prepared){
   	 	   foreach($prepared as $column => $value) {
              if($column !== 'id') {
                 $set[] = "{$column} = {$value}";
              }
   	 	   }	
   	 	}
   	 	$sql .= ' SET '. implode(', ', $set);
   	 	$sql .= ' WHERE id=' . (int) $this->data['id'];
   	 }
   	 // Obtem transação Ativa
   	 if($conn = Transaction::get()) 
     {
   	 	  Transaction::log($sql);
   	 	  $result = $conn->exec($sql);
   	 	  return $result;
   	 } 
     else {
   	 	throw new Exception('Não há transação ativa');
   	 }

   }

   // Método responsável por ler um registro no Banco de Dados e retorná-lo na forma de um objeto
   public function load($id) {
   	# Monta sql SELECT
   	$sql = "SELECT * FROM {$this->getEntity()}";
   	$sql .= ' WHERE id='. (int) $id;

    # Pega a transação ativa
    if($conn = Transaction::get()) {

       # cria msg de log e executa a consulta	
       Transaction::log($sql);
       $result = $conn->query($sql);
       // Se retornou algum dado
       if($result) 
       {
         // Retorna os dados em forma de obj
       	 $object = $result->fetchObject(get_class($this));
       }
       return $object;
    } 
    else {
    	throw new Exception('Não há transação Ativa');
    }

   }

   public function delete($id = NULL ) {
   	 // o ID é o param ou a propriedade
   	$id = $id ? $id : $this->id;

   	// Monta a instrução DELETE
   	$sql  = " DELETE FROM {$this->getEntity()}";
   	$sql .= ' WHERE id='. (int) $this->data['id'];

   	// Obtém a transação ativa
   	if($conn = Transaction::get()) {
   	  // Faz log e executa instrução
    	Transaction::log($sql);
   	  $result = $conn->exec($sql);
   	  return $result; // Retorna resultado	
   	} else {
      throw new Exception('Não há transação ativa');
   	}
   }
   /**
     * Retorna todos objetos
     */
    public static function all()
    {
        $classname = get_called_class();
        $rep = new Repository($classname);
        return $rep->load(new Criteria);
    }
    
    /**
     * Busca um objeto pelo id
    */
   public static function find($id) {
   	  $classname = get_called_class(); // Retorna o nome da classe "Chamado pelo method " 
   	  $ar = new $classname;
   	  return $ar->load($id);
   }

   private function getLast() { 
      if($conn = Transaction::get()) {
      	 $sql  = "SELECT max(id) FROM {$this->getEntity()}";
      	 //cria log executa instrução 
      	 Transaction::log($sql);
      	 $result = $conn->query($sql);

      	 // retorna os dados do banco
      	 $row = $result->fetch();
      	 return $row[0]; 
      } else {
      	throw new Exception('Não há transação ativa !');
      }

   }

   public function prepare($data) {
   	 $prepared = array();
   	 foreach($data as $key => $value) {
        if(is_scalar($value)) {         # Verifica se é uma variável escalar. Ex.: integer, float, string ou boolean
          $prepared[$key] = $this->escape($value);
        }
   	 }
   	 return $prepared;
   }

   public function escape($value) {
   	 if(is_string($value) and (!empty($value))) {
   	 	// adiciona \ em aspas
   	 	$value = addslashes($value);  // Adiciona barras invertidas a uma string
   	    return "'$value'"; 	
   	 } else if(is_bool($value)) { // Se for booleano retorna true or false
        return $value ? 'TRUE' : 'FALSE'; 
   	 } else if($value !== '') {
        return $value;
   	 } else {
   	 	return "NULL";
   	 }
   }


}