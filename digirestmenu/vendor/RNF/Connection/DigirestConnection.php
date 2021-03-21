<?php 
    namespace RNF\Connection;

    abstract class DigirestConnection{

        public static function Connect(){
            try{
                return new \PDO('mysql:host=localhost;dbname=digirestmenu_clientes;charset=utf8', 'root', '');

            }catch(\PDOException $e){
                return $e->getMessage();
            }
        }

    }
?>