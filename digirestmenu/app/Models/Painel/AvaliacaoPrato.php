<?php
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class AvaliacaoPrato{
        private $id;
        private $id_cliente;
        private $id_item;
        private $nota_avaliacao;
        private $comentario;

        function __set($atributo, $valor){
            $this->$atributo = $valor;
        }

        function __get($atributo){
            return $this->$atributo;
        }

        public function insertAvaliacao(){
            $query = 'INSERT INTO tb_avaliacoes_item (id_cliente, id_item, nota_avaliacao, comentario) VALUES (:id_cliente, :id_item, :nota_avaliacao, :comentario)';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id_cliente', $this->id_cliente);
            $stmt->bindValue(':id_item', $this->id_item);
            $stmt->bindValue(':nota_avaliacao', $this->nota_avaliacao);
            $stmt->bindValue(':comentario', $this->comentario);
            return $stmt->execute();
        }

        public function getByDate($data_inicio, $data_fim, $organizar='recente'){
            $query = '
                SELECT 
                    tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                FROM 
                    tb_avaliacoes_item AS tai 
                INNER JOIN 
                    tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                INNER JOIN 
                    tb_itens AS ti ON(tai.id_item = ti.id)
                WHERE 
                tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim ORDER BY tai.data_criacao DESC
            ';

            if($organizar == 'recente'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim ORDER BY tai.data_criacao DESC
                ';
            }
            if($organizar == 'antigo'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim ORDER BY tai.data_criacao ASC
                ';
            }
            if($organizar == 'bem_avaliado'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim ORDER BY tai.nota_avaliacao DESC
                ';
            }
            if($organizar == 'menos_avaliado'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim ORDER BY tai.nota_avaliacao ASC
                ';
            } 

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':data_inicio', Date($data_inicio.' 00:00:00'));
            $stmt->bindValue(':data_fim', Date($data_fim.' 23:59:59'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getByItemDate($data_inicio, $data_fim, $organizar='recente'){
            $query = '
                SELECT 
                    tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                FROM 
                    tb_avaliacoes_item AS tai 
                INNER JOIN 
                    tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                INNER JOIN 
                    tb_itens AS ti ON(tai.id_item = ti.id)
                WHERE 
                tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND tai.id_item IN(:id_item)  ORDER BY tai.data_criacao DESC
            ';

            if($organizar == 'recente'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND tai.id_item IN(:id_item) ORDER BY tai.data_criacao DESC
                ';
            }
            if($organizar == 'antigo'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND tai.id_item IN(:id_item) ORDER BY tai.data_criacao ASC
                ';
            }
            if($organizar == 'bem_avaliado'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND tai.id_item IN(:id_item) ORDER BY tai.nota_avaliacao DESC
                ';
            }
            if($organizar == 'menos_avaliado'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND tai.id_item IN(:id_item) ORDER BY tai.nota_avaliacao ASC
                ';
            } 

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':data_inicio', Date($data_inicio.' 00:00:00'));
            $stmt->bindValue(':data_fim', Date($data_fim.' 23:59:59'));
            $stmt->bindValue(':id_item', $this->id_item);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getBySubgrupoDate($data_inicio, $data_fim, $organizar='recente', $id_subgrupo){
            $query = '
                SELECT 
                    tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item
                FROM 
                    tb_avaliacoes_item AS tai 
                INNER JOIN 
                    tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                INNER JOIN 
                    tb_itens AS ti ON(tai.id_item = ti.id)
                WHERE 
                tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND ti.id_subgrupo IN(:id_subgrupo) ORDER BY tai.data_criacao DESC
            ';

            if($organizar == 'recente'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND ti.id_subgrupo IN(:id_subgrupo) ORDER BY tai.data_criacao DESC
                ';
            }
            if($organizar == 'antigo'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND ti.id_subgrupo IN(:id_subgrupo) ORDER BY tai.data_criacao ASC
                ';
            }
            if($organizar == 'bem_avaliado'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND ti.id_subgrupo IN(:id_subgrupo) ORDER BY tai.nota_avaliacao DESC
                ';
            }
            if($organizar == 'menos_avaliado'){
                $query = '
                    SELECT 
                        tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item
                    FROM 
                        tb_avaliacoes_item AS tai 
                    INNER JOIN 
                        tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                    tai.data_criacao >= :data_inicio AND tai.data_criacao <= :data_fim AND ti.id_subgrupo IN(:id_subgrupo) ORDER BY tai.nota_avaliacao ASC
                ';
            } 

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':data_inicio', Date($data_inicio.' 00:00:00'));
            $stmt->bindValue(':data_fim', Date($data_fim.' 23:59:59'));
            $stmt->bindValue(':id_subgrupo', $id_subgrupo);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getAverageByItem(){
            $query = '
                SELECT 
                    AVG(tai.nota_avaliacao) AS media_notas, ti.nome 
                FROM 
                    tb_avaliacoes_item AS tai                 
                INNER JOIN 
                    tb_itens AS ti ON(tai.id_item = ti.id)
                WHERE 
                    tai.id_item IN(:id_item)
            ';
            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id_item', $this->id_item);
            $stmt->execute();
            $avg = $stmt->fetchAll(\PDO::FETCH_OBJ);
            if($avg[0]->media_notas){
                return $avg;
            }
        }

        public function getAverageBySubgrupo($id_subgrupo){
            
            $con = new Connection;
            $query = '
                SELECT 
                    id 
                FROM 
                    tb_itens 
                WHERE 
                    id_subgrupo IN(:id_subgrupo)
                ORDER BY nome ASC
            ';
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id_subgrupo', $id_subgrupo);
            $stmt->execute();
            $itens = $stmt->fetchAll(\PDO::FETCH_OBJ);
            $averages = [];

            foreach($itens as $value){
                $this->id_item = $value->id;
                $query2 = '
                    SELECT 
                        AVG(tai.nota_avaliacao) AS media_notas, ti.nome 
                    FROM 
                        tb_avaliacoes_item AS tai                 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                        tai.id_item IN(:id_item)                
                ';
                $con = new Connection;
                $stmt = $con->connect()->prepare($query2);
                $stmt->bindValue(':id_item', $this->id_item);
                $stmt->execute();
                $avg =  $stmt->fetch(\PDO::FETCH_OBJ);
                if($avg->media_notas){
                    array_push($averages, $avg);
                }            
            }
                
            return $averages;
        }

        public function getAverageAll(){
            
            $con = new Connection;
            $query = '
                SELECT 
                    id 
                FROM 
                    tb_itens 
                ORDER BY nome ASC
            ';
            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            $itens = $stmt->fetchAll(\PDO::FETCH_OBJ);
            $averages = [];

            foreach($itens as $value){
                $this->id_item = $value->id;
                $query2 = '
                    SELECT 
                        AVG(tai.nota_avaliacao) AS media_notas, ti.nome 
                    FROM 
                        tb_avaliacoes_item AS tai                 
                    INNER JOIN 
                        tb_itens AS ti ON(tai.id_item = ti.id)
                    WHERE 
                        tai.id_item IN(:id_item)
                ';
                $con = new Connection;
                $stmt = $con->connect()->prepare($query2);
                $stmt->bindValue(':id_item', $this->id_item);
                $stmt->execute();
                $avg =  $stmt->fetch(\PDO::FETCH_OBJ);
                if($avg->media_notas){
                    array_push($averages, $avg);
                } 
            }
            return $averages;
        }

        public function getLimit6(){
            $query = '
                SELECT 
                    tai.nota_avaliacao, tai.comentario, DATE_FORMAT(tai.data_criacao, "%d/%m/%Y") AS data_avaliacao, tc.nome AS nome_cliente, ti.nome AS nome_item 
                FROM 
                    tb_avaliacoes_item AS tai 
                INNER JOIN 
                    tb_clientes AS tc ON(tai.id_cliente = tc.id) 
                INNER JOIN 
                    tb_itens AS ti ON(tai.id_item = ti.id)
                ORDER BY tai.data_criacao DESC LIMIT 6
            ';
            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
    }
?>