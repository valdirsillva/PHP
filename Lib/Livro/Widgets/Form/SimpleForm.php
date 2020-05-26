<?php

namespace Livro\Widgets\Form;

class SimpleForm 
{
   private $name, $action, $fields, $title;
   public function __construct($name) 
   {
      $this->name = $name;
      $this->fields = array();
      $this->title  = '';      
   }
   /*
   * Método para setar titulo do formulário
   */
   public function setTitle($title) 
   {
      $this->title = $title;
   }
   /**
   * @param AddField => Método responsável por inserir um campo ao formulário
   */
   public function addField($label, $name, $type, $value, $class = '') 
   {
      $this->fields[] = array('label' => $name, 'name' => $name, 'type' => $type, 'value' => $value, 'class' => $class);
   }

   public function setAction($action) 
   {
   	  $this->action = $action;
   }

   public function show()
   {
   	 print "<div class='panel panel-default' style='margin: 40px;'>\n";
   	 print "<div class='panel-heading'> {$this->title} </div>\n";
   	 print "<div class='panel-body'>\n";
   	 print "<form method='POST' action='{$this->action}' class='form-horizontal' >\n";
   	 if($this->fields) {
        foreach($this->fields as $field) {
           print "<div class='form-group'>\n";
           print "<label class='col-sm-2 control-label'> {$field['label']} </label>\n";
           print "<div class='col-sm-10'>\n";
           print "<input type='{$field['type']}' name='{$field['name']}' 
           value='{$field['value']}' class='{$field['class']}' >\n";
           print "</div>\n";
           print "</div>\n";
        }
        print "<div class='form-group'>\n";
        print "<div class='col-sm-offset-2 col-sm-8'>\n";
        print "<input type='submit' class='btn btn-success' value='enviar'>\n";
        print "</div>\n";
        print "<div>\n";
   	 }
   	 print "</form>";
   	 print "</div>";
   	 print "</div>";
   }
}