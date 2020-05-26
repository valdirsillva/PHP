<?php
namespace Livro\Widgets\Base;
/**
* 07/03/2020
*/
class Element 
{
    protected $tagname;
    protected $properties;
    protected $children;

    public function __construct( $name ) 
    {  /** Define o nome do elemento **/
       $this->tagname = $name;
    }
    /**
    * @param __set: Método para interceptar propriedades de atribuição
    *
    */
    public function __set($name, $value) 
    {  /** Armazena os valores em um array de propriedades **/
       $this->properties[$name] = $value;
    }

    public function __get($name) 
    { /** retorna os valores atribuidos ao array de propriedades **/
       return isset($this->properties[$name]) ? $this->properties[$name] : NULL;
    }
    /**
    * @param add: Permite adicionar conteúdos a tag 
    *
    */

    public function add($child) 
    {
       $this->children[] = $child;
    }

    public function show() 
    {   

    	$this->open(); // Abre a tag
    	print "\n";
    	if($this->children) {
    	   foreach($this->children as $child) {
             if(is_object($child)){ // Testa se é um obj
                $child->show();
             }
             else if((is_string($child)) or (is_numeric($child))) {
                print $child;
             }
    	   }
    	   $this->close(); // Fecha a tag	
    	}

    }
    /**
    * @param open: Exibirá a tag de abertura do HTML
    *
    * @param is_scalar: São variáveis que representam integer, boolean, float ou string
    */

    private function open() 
    {
    	// Exibe a tag de abertura 
    	print "<{$this->tagname}";
    	if($this->properties) 
        {
           /** * Percorre as propriedades do elemento **/
           foreach($this->properties as $name => $value)
            {
              if(is_scalar($value)) 
              {
                 print " {$name}=\"{$value}\"";
              }
           }
    	}
    	print '>'; 
    }
    
    /**
    * @param será exec automaticamente quando o desenvolvedor tratar um obj como string 
    * como aplicar o comando print $objeto. Nesse caso o conteúdo do obj será retornado como string
    */
    public function __toString() 
    {
    	ob_start();
    	$this->show();
    	$content = ob_get_clean();
    	return $content;
    }

    private function close() 
    {   /** Fechamento da tag **/
        print "</{$this->tagname}>\n";
    }

    
}