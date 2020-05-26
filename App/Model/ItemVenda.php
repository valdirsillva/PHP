<?php

use Livro\Database\Record;


/**
* 02/05/2020
*/

class ItemVenda extends Record 
{
	const TABLENAME = 'tb_item_venda';
	private $produto;

	public function set_produto(Produto $p) 
	{
        $this->produto = $p;
        $this->id_produto = $p->id;
	}

	public function get_produto() {
		if (empty($this->produto)) {
           $this->produto = new Produto($this->id_produto);
		}
		return $this->produto;
	}

}