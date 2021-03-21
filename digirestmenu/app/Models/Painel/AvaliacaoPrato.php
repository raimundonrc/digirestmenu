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
    }
?>