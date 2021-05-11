        CREATE TABLE tb_clientes(
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        nome  VARCHAR(100) NOT NULL,
        telefone CHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ); 

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
        status_reserva VARCHAR(50) NOT NULL DEFAULT 'RESERVADO',
        data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id)
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

    CREATE TABLE tb_avaliacoes_item(
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        id_cliente INT NOT NULL,
        id_item INT NOT NULL,
        nota_avaliacao  INT(1) NOT NULL,
        comentario TEXT NULL,
        data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id),
        FOREIGN KEY (id_item) REFERENCES tb_itens(id)
    );

    CREATE TABLE tb_subgrupos(
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        descricao VARCHAR(100) NOT NULL,
        ordem INT NOT NULL,
        aparece_imagem BOOLEAN NOT NULL DEFAULT TRUE,
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
        deleted BOOLEAN NOT NULL DEFAULT FALSE,
        data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        data_update DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
        FOREIGN KEY (id_subgrupo) REFERENCES tb_subgrupos(id)
    );

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