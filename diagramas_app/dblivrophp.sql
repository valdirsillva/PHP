CREATE TABLE funcionario (
  id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
  nome TEXT,
  endereco TEXT,
  email VARCHAR(120),
  departamento INTEGER,
  idiomas TEXT,
  contratacao INTEGER

)DEFAULT CHARSET = utf8;