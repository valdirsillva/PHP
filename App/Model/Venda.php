<?php
use Livro\Database\Transaction;
use Livro\Database\Record;
use Livro\Database\Repository;
use Livro\Database\Criteria;

/**
* 02/05/2020
* @return Class representará a tabela de Vendas, subclasse de Record 
* @return Operações da superclasse load(), store(), delete(), find() all()
*/

class Venda extends Record 
{
	const TABLENAME= 'tb_venda';
	private $itens;
	private $cliente;

	/**
	* @param atribui um cliente (obj pessoa) à venda. Executado automaticammente quando
	* o desenvolvedor definir valor p/ o attr $conta->cliente = new Cliente(1)
	*/

	public function set_cliente(Pessoa $c) 
	{
       $this->cliente = $c;
       $this->id_cliente = $c->id;
	}

	/**
	* Executado quando o desenvol. acessar $conta->cliente. Retorna obj da classe Pessoa 
	* vinculado a classe conta
	*/

	public function get_cliente() 
	{
		if (empty($this->cliente)) {
            $this->cliente = new Pessoa($this->id_cliente);
		}
		return $this->cliente; // Retorna obj instanciado
	}

	public function addItem(Produto $p, $quantidade) 
	{
		$item = new ItemVenda;
		$item->produto    = $p;
		$item->preco      = $p->preco_venda;
		$item->quantidade = $quantidade;
		$this->itens[]    = $item;

		$this->valor_venda += ($this->preco * $quantidade);
	}

	public function store() 
	{
		parent::store(); // Armazena a venda

		/** Percorre os itens da venda */
		foreach ($this->itens as $item) {
           $item->id_venda = $this->id;
           $item->store(); // Armazena o item
		}
	}

	public function get_itens() 
	{
		// Instancia um repositorio de Item
		$repositorio = new Repository('ItemVenda');

		// Define o critério de filtro
		$criteria = new Criteria;
		$criteria->add('id_venda', '=', $this->id);
		$this->itens = $repositorio->load($criteria);  // Carrega a coleção
		return $this->itens;   // retorna os itens
	}
   
    /**
     * Retorna vendas por mes
     */
	public static  function getVendasMes() 
	{  
        $meses = array();
        $meses[1] = 'Janeiro';
        $meses[2] = 'Fevereiro';
        $meses[3] = 'Março';
        $meses[4] = 'Abril';
        $meses[5] = 'Maio';
        $meses[6] = 'Junho';
        $meses[7] = 'Julho';
        $meses[8] = 'Agosto';
        $meses[9] = 'Setembro';
        $meses[10] = 'Outubro';
        $meses[11] = 'Novembro';
        $meses[12] = 'Dezembro';

        $conn = Transaction::get('dbsistemavendas');
        $result = $conn->query("select date_format(data_venda , '%m') as mes, 
        	sum(valor_final) as valor from tb_venda group by 1");
        $dataset = [];

        foreach ($result as $row ) { 
        	$mes = $meses[ (int) $row['mes'] ];
        	$dataset[ $mes ] = $row['valor'];
        }

        return $dataset;

	}

	public static function getVendasTipo() 
	{
		$conn = Transaction::get('dbsistemavendas');
		$result = $conn->query("SELECT tipo.nome as tipo, sum(item_venda.quantidade*item_venda.preco) 
			                      as total
                                    FROM tb_venda as venda, tb_item_venda as item_venda, tb_produto as produto, tb_tipo as tipo
                                   WHERE venda.id = item_venda.id_venda 
                                     AND item_venda.id_produto = produto.id
                                     AND produto.id_tipo = tipo.id
                                GROUP BY 1");

		$dataset = [];
        foreach ($result as $row)
        {
            $dataset[ $row['tipo'] ] = $row['total'];
        }
        
        return $dataset;
	}


}
