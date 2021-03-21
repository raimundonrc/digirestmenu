<?php
    namespace RNF\Connection;

    class Connection {
        public function connect() {
            try{
                return new \PDO(
                    'mysql:host=localhost;dbname='.$_SESSION['client']->bd_nome.';charset=utf8', 
                    'root', 
                    ''
                );

            } catch (\PDOException $e) {
                return $e->getMessage();
            }            
        }
    }
?>