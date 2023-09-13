CREATE TABLE usuario (
id_usuario INT NOT NULL AUTO_INCREMENT,
nome VARCHAR(100) NOT NULL,
cpf VARCHAR(11) NOT NULL UNIQUE,
senha VARCHAR(40) NOT NULL,
tipo CHAR(3) NOT NULL,
idturma INT NOT NULL,
PRIMARY KEY (id_usuario),
FOREIGN KEY (idturma) REFERENCES turma (idturma)
);

CREATE TABLE turma (
idturma INT NOT NULL AUTO_INCREMENT,
nome VARCHAR(50) NOT NULL,
PRIMARY KEY (idturma)
);

alter table turma add id_usuario integer not null default 1;

CREATE TABLE questao (
idquestao INT NOT NULL AUTO_INCREMENT,
descricao VARCHAR(10000) NOT NULL,
id_usuario INT NOT NULL,
alt1 VARCHAR(500),
alt2 VARCHAR(500),
alt3 VARCHAR(500),
alt4 VARCHAR(500),
alt5 VARCHAR(500),
alt_certa INT NOT NULL,
FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
PRIMARY KEY (idquestao)
);

CREATE TABLE prova (
idprova INT NOT NULL AUTO_INCREMENT,
descricao VARCHAR(50) NOT NULL,
situacao CHAR(2) NOT NULL,
data DATE,
hora TIME,
PRIMARY KEY (idprova)
);

CREATE TABLE questao_prova (
idquestprov INT NOT NULL AUTO_INCREMENT,
idprova INT NOT NULL,
idquestao INT NOT NULL,
PRIMARY KEY (idquestprov),
FOREIGN KEY (idprova) REFERENCES prova (idprova),
FOREIGN KEY (idquestao) REFERENCES questao (idquestao)
);

CREATE TABLE resposta (
idresposta INT NOT NULL AUTO_INCREMENT,
id_usuario INT NOT NULL,
idquestprov INT NOT NULL,
alternativa INT NOT NULL,
PRIMARY KEY (idresposta),
FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
FOREIGN KEY (idquestprov) REFERENCES questao_prova (idquestprov)
);

CREATE TABLE aluno_prova (
idalunoprov INT NOT NULL AUTO_INCREMENT,
id_usuario INT NOT NULL,
idprova INT NOT NULL,
PRIMARY KEY (idalunoprov),
FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario),
FOREIGN KEY (idprova) REFERENCES prova (idprova)
);

