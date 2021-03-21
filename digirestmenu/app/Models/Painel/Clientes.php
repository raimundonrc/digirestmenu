<?php
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class Clientes{
        private $id;
        private $nome;
        private $telefone;
        private $email;
        private $data_criacao;
        private $data_update;

        public function __set($attr, $value){
           $this->$attr = $value;
        }

        public function __get($attr){
            return $this->$attr;
        }

        public function getByEmail(){
            $query = 'SELECT id, nome, telefone, email, data_criacao, data_update FROM tb_clientes WHERE email IN(:email)';
            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':email', $this->email);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        public function insertClient(){
            $query = 'INSERT INTO tb_clientes (nome, telefone, email) VALUES (:nome, :telefone, :email)';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':nome', $this->nome);
            $stmt->bindValue(':telefone', $this->telefone);
            $stmt->bindValue(':email', $this->email);
            return $stmt->execute();            
        }
    }
?>