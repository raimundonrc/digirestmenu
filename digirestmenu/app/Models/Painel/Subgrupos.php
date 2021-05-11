<?php 
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class Subgrupos{
        private $id;
        private $descricao;
        private $ordem;
        private $ativo;
        private $aparece_imagem;
        private $data_criacao;
        private $data_update;

        public function __get($attr){
            return $this->$attr;
        }
    
        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function getAll(){
            $query = 'SELECT id, descricao, ordem, ativo, aparece_imagem, data_criacao, data_update FROM tb_subgrupos ORDER BY ordem';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function getAllActive(){
            $query = 'SELECT id, descricao, ordem, ativo, aparece_imagem, data_criacao, data_update FROM tb_subgrupos WHERE ativo = TRUE ORDER BY ordem';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function insertSubgrupo(){
            $query = 'SELECT MAX(ordem) as ordem FROM tb_subgrupos';

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':ordem', $this->ordem, \PDO::PARAM_INT);
            $stmt->execute();
            $ordem = $stmt->fetch(\PDO::FETCH_OBJ);

            $query = 'INSERT INTO tb_subgrupos (descricao, ordem, aparece_imagem) VALUES  (:descricao, :ordem, :aparece_imagem)';
            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':descricao', $this->descricao);
            $stmt->bindValue(':ordem', ($ordem->ordem + 1), \PDO::PARAM_INT);
            $stmt->bindValue(':aparece_imagem', $this->aparece_imagem);

            $stmt->execute();
            return true;
        }

        public function setOrdens(){
            $query = 'SELECT ordem FROM tb_subgrupos WHERE id IN(:id)';//Busca a ordem inicial do id

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->execute();
            $ordemInicio = $stmt->fetch(\PDO::FETCH_OBJ);

            if($ordemInicio->ordem < $this->ordem){//Ordem início < ordem final
                $query = 'SELECT id, ordem FROM tb_subgrupos WHERE ordem > :ordemInicio AND ordem <= :ordemFinal AND id NOT IN(:id) ORDER BY ordem'; 

                $con = new Connection;

            $stmt = $con->connect()->prepare($query);
                $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
                $stmt->bindValue(':ordemInicio', $ordemInicio->ordem, \PDO::PARAM_INT);
                $stmt->bindValue(':ordemFinal', $this->ordem, \PDO::PARAM_INT);
                $stmt->execute();
                $ordens = $stmt->fetchAll(\PDO::FETCH_OBJ);

                foreach($ordens as $value){//Ordens posteriores são subtraídas, liberando a ordem informada pelo usuário
                    $update = 'UPDATE tb_subgrupos SET ordem = :novaOrdem WHERE id IN(:id)';

                    $stmt = Connection::connect()->prepare($update);
                    $stmt->bindValue(':novaOrdem', ($value->ordem -  1), \PDO::PARAM_INT);
                    $stmt->bindValue(':id', $value->id, \PDO::PARAM_INT);

                    $stmt->execute();
                }
            
            } else {//Ordem início > ordem final
                $query = 'SELECT id,  ordem FROM tb_subgrupos WHERE ordem < :ordemInicio AND ordem >= :ordemFinal AND id NOT IN(:id) ORDER BY ordem';

                $con = new Connection;

            $stmt = $con->connect()->prepare($query);
                $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
                $stmt->bindValue(':ordemInicio', $ordemInicio->ordem, \PDO::PARAM_INT);
                $stmt->bindValue(':ordemFinal', $this->ordem, \PDO::PARAM_INT);
                $stmt->execute();
                $ordens = $stmt->fetchAll(\PDO::FETCH_OBJ);

                foreach($ordens as $value){//Ordens anteriores são somadas, liberando a ordem informada pelo usuário
                    $update = 'UPDATE tb_subgrupos SET ordem = :novaOrdem WHERE id IN(:id)';
                    $stmt = Connection::connect()->prepare($update);
                    $stmt->bindValue(':novaOrdem', ($value->ordem +  1), \PDO::PARAM_INT);
                    $stmt->bindValue(':id', $value->id, \PDO::PARAM_INT);

                    $stmt->execute();
                }                
            }

            $update = 'UPDATE tb_subgrupos SET ordem = :ordem WHERE id IN(:id)';
            $stmt = Connection::connect()->prepare($update);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':ordem', $this->ordem, \PDO::PARAM_INT);
            $stmt->execute();

            return true;
        }

        public function setSubgrupo(){
            $query = 'UPDATE tb_subgrupos SET descricao = :descricao, ativo = :ativo, aparece_imagem = :aparece_imagem WHERE id IN(:id)';           

            $con = new Connection;

            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':descricao', $this->descricao);
            $stmt->bindValue(':aparece_imagem', $this->aparece_imagem);
            $stmt->bindValue(':ativo', $this->ativo, \PDO::PARAM_BOOL);
            $stmt->execute();
            return true;
        }
    }
?>