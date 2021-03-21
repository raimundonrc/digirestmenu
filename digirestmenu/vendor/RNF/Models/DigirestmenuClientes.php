<?php 
    namespace RNF\Models;
    use RNF\Connection\DigirestConnection;

    class DigirestmenuClientes{
        public function getAll(){
            $query = 'SELECT estabelecimento, rota_raiz, bd_nome FROM digirestmenu_clientes';

            $stmt = DigirestConnection::connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
    }
?>