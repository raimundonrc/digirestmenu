<?php
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class Reservas{
        private $id;
        private $id_cliente;
        private $nome;
        private $telefone;
        private $email;
        private $qtd_pessoas;
        private $data_reserva;
        private $hora_reserva;
        private $observacao;
        private $status_reserva;
        private $data_criacao;
        private $data_update;

        public function __get($attr){
            return $this->$attr;
        }

        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function getAll(){
            $query = 'SELECT id, nome, telefone, email, qtd_pessoas, DATE_FORMAT(data_reserva, "%d/%m/%Y") AS data_reserva, DATE_FORMAT(hora_reserva,"%H:%i") AS hora_reserva, observacao, status_reserva, data_criacao  FROM tb_reservas WHERE data_reserva IN(:date) ORDER BY status_reserva DESC, hora_reserva';
            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':date', $this->data_reserva);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function insertReserva(){
            $query = 'INSERT INTO tb_reservas (id_cliente, nome, telefone, email, qtd_pessoas, data_reserva, hora_reserva, observacao) VALUES (:id_cliente, :nome, :telefone, :email, :qtd_pessoas, :data_reserva, :hora_reserva, :observacao)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id_cliente', $this->id_cliente);
            $stmt->bindValue(':nome', $this->nome);
            $stmt->bindValue(':telefone', $this->telefone);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':qtd_pessoas', $this->qtd_pessoas);
            $stmt->bindValue(':data_reserva', $this->data_reserva);
            $stmt->bindValue(':hora_reserva', $this->hora_reserva);
            $stmt->bindValue(':observacao', $this->observacao);
            return $stmt->execute();
        }

        public function setStatus(){
            $query = 'UPDATE tb_reservas SET status_reserva = :status WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':status', $this->status_reserva);
            $stmt->execute(); 
            return true;           
        }

        public function setReserva(){
            $query = 'UPDATE tb_reservas SET nome=:nome, telefone=:telefone, email=:email, qtd_pessoas=:qtd_pessoas, data_reserva=:data_reserva, hora_reserva=:hora_reserva, observacao=:observacao WHERE id IN(:id)';   
            
            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':nome', $this->nome);
            $stmt->bindValue(':telefone', $this->telefone);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':qtd_pessoas', $this->qtd_pessoas, \PDO::PARAM_INT);
            $stmt->bindValue(':data_reserva', $this->data_reserva);
            $stmt->bindValue(':hora_reserva', $this->hora_reserva);
            $stmt->bindValue(':observacao', $this->observacao);
            $stmt->execute();
            return true;
        }
    }