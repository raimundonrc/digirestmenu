<?php 
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class Estabelecimento{
        private $id;
        private $razao_social;
        private $estabelecimento;
        private $email;
        private $senha;
        private $foto_logo;
        private $whatsapp;
        private $instagram;
        private $facebook;
        private $twitter;
        private $tripAdvisor;
        private $data_criacao;
        private $data_update;

        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function __get($attr){
            return $this->$attr;
        }

        public function getAll(){
            $query = 'SELECT id, razao_social, estabelecimento, foto_logo, whatsapp, instagram, facebook, twitter, tripAdvisor, data_criacao, data_update FROM tb_estabelecimento';
            
            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        public function getEstabelecimentoByRoute(){
            $query = 'SELECT id, razao_social, estabelecimento, foto_logo, whatsapp, instagram, facebook, twitter, tripAdvisor, data_criacao, data_update FROM tb_estabelecimento WHERE rota_raiz IN(:rota_raiz)';
            
            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':rota_raiz', $_SESSION['client']->rota_raiz);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        public function login(){
            $query = 'SELECT rota_raiz FROM tb_estabelecimento WHERE email IN(:email) AND senha IN(:senha)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':senha', $this->senha);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ);
        }

        public function getLogotipo(){
            $query = 'SELECT foto_logo FROM tb_estabelecimento WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_OBJ)->foto_logo;
        }

        public function setLogotipo(){
            $query = 'UPDATE tb_estabelecimento SET foto_logo = :foto_logo WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':foto_logo', $this->foto_logo);
            return $stmt->execute();
        }

        public function setNome(){
            $query = 'UPDATE tb_estabelecimento SET estabelecimento = :estabelecimento WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':estabelecimento', $this->estabelecimento);
            return $stmt->execute();
        }

        public function setWhatsapp(){
            $query = 'UPDATE tb_estabelecimento SET whatsapp = :whatsapp WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':whatsapp', $this->whatsapp);
            return $stmt->execute();
        }

        public function setInstagram(){
            $query = 'UPDATE tb_estabelecimento SET instagram = :instagram WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':instagram', $this->instagram);
            return $stmt->execute();
        }

        public function setFacebook(){
            $query = 'UPDATE tb_estabelecimento SET facebook = :facebook WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':facebook', $this->facebook);
            return $stmt->execute();
        }

        public function setTwitter(){
            $query = 'UPDATE tb_estabelecimento SET twitter = :twitter WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':twitter', $this->twitter);
            return $stmt->execute();
        }

        public function setTripadvisor(){
            $query = 'UPDATE tb_estabelecimento SET tripadvisor = :tripadvisor WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            $stmt->bindValue(':tripadvisor', $this->tripadvisor);
            return $stmt->execute();
        }
    }
?>