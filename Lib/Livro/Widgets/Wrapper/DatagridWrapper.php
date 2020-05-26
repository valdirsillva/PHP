<?php

namespace Livro\Widgets\Wrapper;

use Livro\Widgets\Container\Panel;
use Livro\Widgets\Datagrid\Datagrid;
use Livro\Widgets\Base\Element;

class DatagridWrapper 
{
    private $decorated;

    public function __construct(Datagrid $datagrid) 
    {
        $this->decorated = $datagrid;
    }

    /**
    * Redirecionar chamadas para o obj decorado
    *
    */
    public function __call($method, $parameters) 
    {
        return call_user_func_array(array($this->decorated, $method), $parameters); 
    }

    public function __set($attribute, $value) 
    {
        $this->decorated->$attribute = $value;
    }

     /**
     * Exibe a datagrid
     */
    public function show()
    {
        $element = new Element('table');
        $element->class = 'table table-striped table-hover';
        
        // cria o header
        $thead = new Element('thead');
        $element->add($thead);
        $this->createHeaders($thead);
        
        // cria o body
        $tbody = new Element('tbody');
        $element->add($tbody);
        
        $items = $this->decorated->getItems();
        foreach ($items as $item)
        {
            $this->createItem($tbody, $item);
        }
        
        $panel = new Panel;
        $panel->type = 'datagrid';
        $panel->add($element);
        $panel->show();
    }


    public function createHeaders($thead) 
    {
        // adiciona uma linha à linha 
        $row = new Element('tr');
        $thead->add($row);

        $actions = $this->decorated->getActions();
        $columns = $this->decorated->getColumns();

        // adiciona células para ações
        if($actions) {
           foreach($actions as $action) {
             $celula = new Element('th');
             $celula->width = '40px';
             $row->add($celula);
           }
        }

        // adiciona as células para os títulos das colunas
        if($columns) {
           // percorre as colunas da listagem
            foreach($columns as $column) {
               //obtém as propriedades da coluna
               $label = $column->getLabel();
               $align = $column->getAlign();
               $width = $column->getWidth();

               $celula = new Element('th');
               $celula->add($label);
               $celula->style = "text-align:$align";
               $celula->width = $width;
               $row->add($celula);

               // verifica se a coluna tem uma ação
               if($column->getAction()) {

                  $url = $column->getAction();
                  $celula->onclick = "document.location='$url'";
               } 
            }
        }
    }
    /**
    * @param createItems: Executado sobre cada item a ser adicionado na datagrid
    * Este método cria uma (tr) e inicialmente percorre as ações (getActions) da datagrid
    *
    *
    */
    public function createItem($tbody, $item) 
    {
        $row = new Element('tr');
        $tbody->add($row);

        $actions = $this->decorated->getActions();
        $columns = $this->decorated->getColumns();

        /** ** Verifica se a listagem possui ações **/
        if ($actions) {
           /** Percorre as ações **/
           foreach($actions as $action) {
              /** ** Obtém as propriedades das ações **/
              $url   = $action['action']->serialize();
              $label = $action['label'];
              $image = $action['image'];
              $field = $action['field'];

              /** ** Obtém o campo do objeto que será passado adiante **/
              $key = $item->$field;

              // cria um link
              $link = new Element('a');
              $link->href = "{$url}&key={$key}&{$field}={$key}";



              /** ** verifica se o link será com imagem ou com text **/
              if($image) {
                /** adiciona a imagem ao link **/
                $i = new Element('i');
                $i->class = $image;
                $i->title = $label;
                $i->add('');
                $link->add($i); 
              }
              else {
                // add o rótulo de texto ao link
                $link->add($label);
              }

              $element = new Element('td');
              $element->add($link);
              $element->align = 'center';

              // adiciona a célula à linha
              $row->add($element);
           }
        }

        if($columns) {
          /** ** Percorre as colunas da Datagrid **/
          foreach($columns as $column) {
             /** ** Obtémas propriedades da coluna  **/
             $name     = $column->getName();
             $align    = $column->getAlign();
             $width    = $column->getWidth();
             $function = $column->getTransformer();
             $data     = $item->$name;

             // Verifica se há função para transformar os dados
             if($function) {
                /** Aplica a função sobre os dados **/
                $data = call_user_func($function, $data);
             } 

             $element = new Element('td');
             $element->add($data);
             $element->align = $align;
             $element->width = $width;

             /** ** Adiciona a célula na linha **/
             $row->add($element);
          }
        }
    }
}