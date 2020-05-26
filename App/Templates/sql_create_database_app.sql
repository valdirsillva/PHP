
CREATE DATABASE db_sistema_vendas
default character set utf8
default collate utf8_general_ci;

CREATE TABLE tb_estado (
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	sigla CHAR(2),
	nome TEXT
    
)DEFAULT CHARSET = utf8;


CREATE TABLE tb_cidade (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   nome TEXT NOT NULL,
   id_estado INTEGER,
   FOREIGN KEY (id_estado) INTEGER REFERENCES tb_estado(id)
   
)DEFAULT CHARSET = utf8;


CREATE TABLE tb_grupo (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   nome TEXT NOT NULL,
  
)DEFAULT CHARSET = utf8;


CREATE TABLE tb_fabricante (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   nome TEXT NOT NULL,
   site TEXT

)DEFAULT CHARSET = utf8;



CREATE TABLE tb_unidade (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   sigla TEXT,
   nome  TEXT

)DEFAULT CHARSET = utf8;



CREATE TABLE tb_tipo (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   nome TEXT,
)DEFAULT CHARSET = utf8;



CREATE TABLE tb_produto (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   descricao TEXT,
   estoque INT,
   preco_custo DECIMAL(1, 3),
   preco_venda DECIMAL(1, 3),
   id_fabricante INTEGER,
   id_unidade INTEGER,
   id_tipo INTEGER,
   FOREIGN KEY (id_fabricante) INTEGER REFERENCES tb_fabricante(id),
   FOREIGN KEY (id_unidade) INTEGER REFERENCES tb_unidade(id),
   FOREIGN KEY (id_tipo) INTEGER REFERENCES tb_tipo(id)
   
)DEFAULT CHARSET = utf8;


CREATE TABLE tb_pessoa (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   nome TEXT,
   endereco TEXT,
   bairro TEXT,
   telefone TEXT,
   email TEXT,
   id_cidade INTEGER,
   FOREIGN KEY (id_cidade) INTEGER REFERENCES tb_cidade(id)
   
)DEFAULT CHARSET = utf8;



CREATE TABLE tb_venda (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   id_cliente INTEGER,
   data_venda date,
   valor_venda DECIMAL(1,3) NOT NULL,
   desconto DECIMAL(1,3),
   acrescimos DECIMAL(1, 3),
   valor_final DECIMAL(1, 3),
   obS TEXT,
   FOREIGN KEY (id_cliente) INTEGER REFERENCES tb_pessoa(id)
   
)DEFAULT CHARSET = utf8;



CREATE TABLE tb_item_venda (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   nome TEXT,
   id_produto INTEGER,
   id_venda   INTEGER,
   quantidade INT,
   preco DECIMAL(1, 3),
   FOREIGN KEY (id_produto) INTEGER REFERENCES tb_produto(id),
   FOREIGN KEY (id_venda) INTEGER REFERENCES tb_venda(id)
   
)DEFAULT CHARSET = utf8;



CREATE TABLE tb_conta (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   id_cliente INTEGER,
   dt_emissao date,
   dt_vencimento date,
   valor DECIMAL(1, 3),
   paga CHAR(1),
   FOREIGN KEY (id_cliente) INTEGER REFERENCES tb_pessoa(id)
   
)DEFAULT CHARSET = utf8;



CREATE TABLE tb_pessoa_grupo (
   id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
   id_pessoa INTEGER,
   id_grupo INTEGER,
   FOREIGN KEY (id_pessoa) INTEGER REFERENCES tb_pessoa(id)
   FOREIGN KEY (id_grupo) INTEGER REFERENCES tb_grupo(id)
   
)DEFAULT CHARSET = utf8;
