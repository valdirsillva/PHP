<?php

use Livro\Database\Record;
use Livro\Database\Repository;
use Livro\Database\Criteria;

class Pessoa extends Record
{
	const TABLENAME = 'tb_pessoa';
    private $cidade;
    
    /**
    * @return cidade 
    */
    public function get_cidade() 
    {
        if(empty($this->cidade)) {
          $this->cidade = new Cidade($this->id_cidade);
        }
        return $this->cidade;
    }
    /**
    *
    * @return name cidade
    */
    public function get_nome_cidade() 
    {
        if(empty($this->cidade)) {
           $this->cidade = new Cidade($this->id_cidade);
        }
        return $this->cidade->nome;
    }
    
    public function addGrupo(Grupo $grupo) 
    {
        $pg = new PessoaGrupo;
        $pd->id_grupo  = $grupo->id;
        $pg->id_pessoa = $this->id;
        $pg->store();
    }
    /**
    *
    * @return Elimina o vinculo de uma pessoa c/ um grupo
    */
    public function delGrupos() 
    {   
        $criteria = new Criteria;
        $criteria->add('id_pessoa', '=', $this->id);

        $repositorio = new Repository('PessoaGrupo');
        return $repositorio->delete($criteria);
    }
    
    /**
    *
    * @return Retorna um array de obj do tipo vinculado à pessoa
    *
    */
    
    public function getGrupos() 
    {
        $grupos = array();
        $criteria = new Criteria;
        $criteria->add('id_pessoa', '=', $this->id);
        $repositorio = new Repository('PessoaGrupo');
        $vinculos = $repositorio->load($criteria);
        if ($vinculos) {
            foreach($vinculos as $vinculo) {
                $grupos[] = new Grupo($vinculo->id_grupo);
            }
        }
        return $grupos;
    }

    public function getIdsGrupos() 
    {
        $grupos_ids = array();
        $grupos = $this->getGrupos();
        if($grupos) {
           foreach($grupos as $grupo) {
              $grupos_ids[] = $grupo->id;
           } 
        }
        return $grupos_ids;
    }

    public function getContasEmAberto() 
    {
        return Conta::getByPessoa($this->id);
    }

    public function totalDebitos() 
    {
        return Conta::debitosPorPessoa($this->id);
    }
}