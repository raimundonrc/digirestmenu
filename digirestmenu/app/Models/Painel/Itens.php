<?php 
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class Itens{
        private $id;
        private $nome;
        private $descricao;
        private $preco;
        private $id_subgrupo;
        private $foto;
        private $destaque;
        private $ativo;
        private $deleted;
        private $data_criacao;
        private $data_update;

        public function __get($attr){
            return $this->$attr;
        }

        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function getAll(){
            $query = 'SELECT id, nome, descricao, preco, id_subgrupo, foto, destaque, ativo, data_criacao, data_update FROM tb_itens WHERE deleted IN(FALSE) ORDER BY ativo DESC, nome ASC';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getAllActive(){
            $query = 'SELECT id, nome, descricao, preco, id_subgrupo, foto, destaque, ativo, data_criacao, data_update FROM tb_itens WHERE deleted IN(FALSE) AND ativo IN(TRUE) ORDER BY nome ASC';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getDestaques(){
            $query = 'SELECT id, foto FROM tb_itens WHERE deleted IN(FALSE) AND destaque IN(TRUE) AND ativo IN(TRUE) ORDER BY nome ASC';
            
            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getBySubgrupo(){
            $query = 'SELECT id, nome, descricao, preco, id_subgrupo, foto, destaque, ativo, data_criacao, data_update FROM tb_itens WHERE deleted IN(FALSE) AND id_subgrupo IN(:id_subgrupo) ORDER BY ativo DESC, nome ASC';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id_subgrupo', $this->id_subgrupo, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function insertItem(){
            $query = 'INSERT INTO tb_itens (nome, descricao, preco, id_subgrupo, foto, ativo) VALUES (:nome, :descricao, :preco, :id_subgrupo, :foto, :ativo)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':nome', $this->nome);
            $stmt->bindValue(':descricao', $this->descricao);
            $stmt->bindValue(':preco', $this->preco);
            $stmt->bindValue(':id_subgrupo', $this->id_subgrupo, \PDO::PARAM_INT);
            $stmt->bindValue(':foto', $this->foto);
            $stmt->bindValue(':ativo', $this->ativo, \PDO::PARAM_BOOL);
            
            return $stmt->execute();
        }

        public function setItem(){
            $query = 'UPDATE tb_itens SET nome = :nome, descricao = :descricao, preco = 
                    :preco, id_subgrupo = :id_subgrupo, destaque = :destaque, ativo = :ativo WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':nome', $this->nome);
            $stmt->bindValue(':descricao', $this->descricao);
            $stmt->bindValue(':preco', $this->preco);
            $stmt->bindValue(':id_subgrupo', $this->id_subgrupo, \PDO::PARAM_INT);
            if(isset($this->foto) && $this->foto != '')
                $stmt->bindValue(':foto', $this->foto);
            $stmt->bindValue(':destaque', $this->destaque, \PDO::PARAM_BOOL);
            $stmt->bindValue(':ativo', $this->ativo, \PDO::PARAM_BOOL);
            return $stmt->execute();
        }

        public function deleteFoto(){
            $query = 'UPDATE tb_itens SET foto = null WHERE id in(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);

            return $stmt->execute();
        }

        public function setFoto(){
            $query = 'UPDATE tb_itens SET foto = :foto WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':foto', $this->foto);

            return $stmt->execute();
        }

        public function deleteItem(){
            $query = 'UPDATE tb_itens SET deleted = TRUE WHERE id IN(:id)';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);

            return $stmt->execute();
        }
    }
?>