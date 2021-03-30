<?php
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class AvaliacoesEstabelecimento{
        private $id;
        private $id_cliente;
        private $nota_avaliacao;
        private $comentario;
        private $data_criacao;
        private $data_update;

        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function __get($attr){
            return $this->$attr;
        }

        public function getLimit6(){
            $query = '
                SELECT 
                    tae.nota_avaliacao, tae.comentario, DATE_FORMAT(tae.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome 
                FROM 
                    tb_avaliacoes_estabelecimento AS tae INNER JOIN tb_clientes AS tc ON(tae.id_cliente = tc.id)
                ORDER BY data_avaliacao DESC LIMIT 6;
            ';
            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function insertAvaliacao(){
            $query = 'INSERT INTO tb_avaliacoes_estabelecimento (id_cliente, nota_avaliacao, comentario) VALUES (:id_cliente, :nota_avaliacao, :comentario)';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id_cliente', $this->id_cliente);
            $stmt->bindValue(':nota_avaliacao', $this->nota_avaliacao);
            $stmt->bindValue(':comentario', $this->comentario);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getAverage(){
            $query = 'SELECT AVG(nota_avaliacao) AS media_notas FROM tb_avaliacoes_estabelecimento';
            $con = new Connection;
            return $con->connect()->query($query)->fetch(\PDO::FETCH_OBJ);
        }

        public function getByDate($data_inicio, $data_fim, $organizar='recente'){
            $query = '
                SELECT 
                    tae.nota_avaliacao, tae.comentario, DATE_FORMAT(tae.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome 
                FROM 
                    tb_avaliacoes_estabelecimento AS tae INNER JOIN tb_clientes AS tc ON(tae.id_cliente = tc.id)
                WHERE 
                tae.data_criacao >= :data_inicio AND tae.data_criacao <= :data_fim ORDER BY tae.data_criacao DESC
            ';

            if($organizar == 'recente'){
                $query = '
                    SELECT 
                        tae.nota_avaliacao, tae.comentario, DATE_FORMAT(tae.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome 
                    FROM 
                        tb_avaliacoes_estabelecimento AS tae INNER JOIN tb_clientes AS tc ON(tae.id_cliente = tc.id)
                    WHERE 
                    tae.data_criacao >= :data_inicio AND tae.data_criacao <= :data_fim ORDER BY tae.data_criacao DESC
                ';
            }
            if($organizar == 'antigo'){
                $query = '
                    SELECT 
                        tae.nota_avaliacao, tae.comentario, DATE_FORMAT(tae.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome 
                    FROM 
                        tb_avaliacoes_estabelecimento AS tae INNER JOIN tb_clientes AS tc ON(tae.id_cliente = tc.id)
                    WHERE 
                    tae.data_criacao >= :data_inicio AND tae.data_criacao <= :data_fim ORDER BY tae.data_criacao ASC
                ';
            }
            if($organizar == 'bem_avaliado'){
                $query = '
                    SELECT 
                        tae.nota_avaliacao, tae.comentario, DATE_FORMAT(tae.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome 
                    FROM 
                        tb_avaliacoes_estabelecimento AS tae INNER JOIN tb_clientes AS tc ON(tae.id_cliente = tc.id)
                    WHERE 
                    tae.data_criacao >= :data_inicio AND tae.data_criacao <= :data_fim ORDER BY tae.nota_avaliacao DESC
                ';
            }
            if($organizar == 'menos_avaliado'){
                $query = '
                    SELECT 
                        tae.nota_avaliacao, tae.comentario, DATE_FORMAT(tae.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome 
                    FROM 
                        tb_avaliacoes_estabelecimento AS tae INNER JOIN tb_clientes AS tc ON(tae.id_cliente = tc.id)
                    WHERE 
                    tae.data_criacao >= :data_inicio AND tae.data_criacao <= :data_fim ORDER BY tae.nota_avaliacao ASC
                ';
            } 

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':data_inicio', Date($data_inicio.' 00:00:00'));
            $stmt->bindValue(':data_fim', Date($data_fim.' 23:59:59'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
    }
?>