This is a system for digital menu, reservations and reviews for restaurants, bars, cafeterias...


HOW TO USE:


DATA BASES:
Important! Each establishment in the system must have a database with a unique name, as the databases are individual for each establishment.
In addition, there should be a general database to store mainly the name of the databases and the root route of the establishment.


GENERAL DATABASE TABLES:
The general database should have a table with the name "digirestmenu_clientes", and the following columns:
- id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
- estabelecimento VARCHAR(100) NOT NULL,
- rota_raiz VARCHAR(50) NOT NULL UNIQUE,
- bd_nome VARCHAR(50) NOT NULL UNIQUE,
- data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
- data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

Manually fill in the data in this table:
- estabelecimento => Name of the establishment (Will show on the pages),
- rota raiz => The name of the establishment root route with "/". Example: /client_name,
- bd_nome => The name of the customer database


ESTABLISHMENT DATABASE:
The establishment's database must have the following tables:
/*****************************************************************************************************
The following columns in the "tb_establishment" table are mandatory and must be completed manually:
- estabelecimento => Name of the establishment.
- rota_raiz => Root route of the establishment. This column defines which route will be released when you log in: Important! The root route must be the same as the root route of the "digirestmenu_clientes" table, otherwise it will not work.
CREATE ADMIN LOGIN:
- email => E-mail for the establishment's login.
- senha => Password for the login of the establishment. Important! The password must be written to the database using MD5 hash.

CREATE TABLE tb_estabelecimento(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    razao_social VARCHAR(200) NULL, 
    estabelecimento VARCHAR(100) NOT NULL,
    rota_raiz VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(100) NOT NULL,
    foto_logo VARCHAR(100) NULL,
    whatsapp CHAR(15) NULL,
    instagram VARCHAR(100) NULL,
    facebook VARCHAR(100) NULL,
    twitter VARCHAR(100) NULL,
    tripAdvisor VARCHAR(100) NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/*** The tables below will be filled in by the users of the system (Do not require manual filling). ***/

CREATE TABLE tb_reservas(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_cliente  INT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NULL,
    telefone CHAR(15) NOT NULL,
    qtd_pessoas INT(3) NOT NULL,
    data_reserva DATE NOT NULL,
    hora_reserva TIME NOT NULL,
    observacao VARCHAR(500) NULL,
    status_reserva VARCHAR(50) NOT NULL DEFAULT('RESERVADO'),
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id)
);

CREATE TABLE tb_clientes(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome  VARCHAR(100) NOT NULL,
    telefone CHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE tb_avaliacoes_estabelecimento(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_cliente INT NOT NULL,
    nota_avaliacao  INT(1) NOT NULL,
    comentario TEXT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id)
);

CREATE TABLE tb_subgrupos(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(100) NOT NULL,
    ordem INT NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
);

CREATE TABLE tb_itens(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(500) NULL,
    preco FLOAT(8,2) NOT NULL,
    id_subgrupo INT NOT NULL,
    foto VARCHAR(100) NULL,
    destaque BOOLEAN NOT NULL DEFAULT FALSE,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (id_subgrupo) REFERENCES tb_subgrupos(id)
);

CREATE TABLE tb_horarios_reserva(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    dia_da_semana CHAR(3) NOT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE tb_horarios_bloqueio(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    data_bloqueio DATE NOT NULL,
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
**********************************************************************************************/


SET DATABASES CONFIGURATIONS:
General database:
Access the DigirestConnection.php file at digirestmenu/vendor/RNF/Connection.
Establishment database:
Access the Connection.php file at digirestmenu/vendor/RNF/Connection.
Important! All establishment databases must have the same password.


ACCESS ROUTES IN THE BROWSER:
All routes are prefixed with the client root route defined in the general database. Example: http://localhost/client_name/cardapio.

Routes for system users:
Menu - Products menu => /client_name/cardapio
Reservation - Client bookings for the establishment  => /client_name/reservas
Administration - Administrator panel with system settings and reports => /client_name/painel