<?php

namespace Livro\Traits;

use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;
use Livro\Widgets\Dialog\Message;
use Exception;
/** 05/05/2020
* @param => Trait é formado por um conjunto de métodos que representam uma funcionalidade e que pode
* ser usada por diversas classes
*/

trait ReloadTrait 
{
	function onReload() 
	{
		try {
		   Transaction::open( $this->connection );
		   $repository = new Repository( $this->activeRecord ); // cria um repositório

		   /** Cria critério se seleção **/
		   $criteria = new Criteria;
		   $criteria->setProperty('order', 'id');
		   /** Verifica se há filtro predefinido **/
		   if(isset($this->filters)) {
              foreach($this->filters as $filter) 
              {
                 $criteria->add($filter[0], $filter[1], $filter[2],  $filter[3]);
              }
		   }

		   /** Carrega os objetos que satisfazem o critério **/
		   $objects = $repository->load($criteria);
		   $this->datagrid->clear();

		   if ($objects) {
               foreach($objects as $object) 
               {
                  // add o obj ao datagrid
               	  $this->datagrid->addItem($object);
               }
		   } 

		   Transaction::close(); 	

		} catch(Exception $e) {

           new Message('error', $e->getMessage());
		}
	}
}