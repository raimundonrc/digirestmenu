<?php 
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class HorariosBloqueio{
        private $id;
        private $horario_inicio;
        private $horario_fim;
        private $data_bloqueio;
        private $data_criacao;
        private $data_update;

        public function __get($attr){
            return $this->$attr;
        }

        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function getAll(){
            $query = 'SELECT id, DATE_FORMAT(horario_inicio, "%H:%i") AS horario_inicio,  DATE_FORMAT(horario_fim, "%H:%i") AS horario_fim, data_criacao, data_update FROM tb_horarios_bloqueio WHERE data_bloqueio in(:data_bloqueio) ORDER BY horario_inicio';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':data_bloqueio', $this->data_bloqueio);
            $stmt->execute();
            $horario = $stmt->fetchAll(\PDO::FETCH_OBJ);

            $hor = Date('00:00');
            $horarios = array();
            foreach($horario as $hora){
                $ini= explode(':', $hora->horario_inicio);
                $fin= explode(':', $hora->horario_fim);

                if($hora->horario_inicio == $hora->horario_fim){
                    array_push($horarios, $hora->horario_inicio);
                }
                
                while($ini[0] <= $fin[0]){ //Cria as horas do intervalo  

                    if($ini[1] > 59){
                        $ini[1] = 0;
                    }

                    while($ini[1] <= 59){  //Cria os minutos do intervalo

                        if($hor >= $hora->horario_fim) {
                        break;
                        }  

                        $hor = Date(str_pad($ini[0], 2, '0', STR_PAD_LEFT).':'.str_pad($ini[1], 2, '0', STR_PAD_LEFT));//Cria os horários do intervalo
                        array_push($horarios, $hor);
                        $ini[1] += 15;
                    }
                    $ini[0] ++;
                }
            }

            return $horarios;
        }

        public function getIntervalo(){
            $query = 'SELECT id, DATE_FORMAT(horario_inicio, "%H:%i") AS horario_inicio,  DATE_FORMAT(horario_fim, "%H:%i") AS horario_fim, data_criacao, data_update FROM tb_horarios_bloqueio WHERE data_bloqueio in(:data_bloqueio) ORDER BY horario_inicio';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':data_bloqueio', $this->data_bloqueio);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function insertBloqueio(){
            $query = 'INSERT INTO tb_horarios_bloqueio (data_bloqueio, horario_inicio, horario_fim) VALUES (:data_bloqueio, :horario_inicio, :horario_fim)';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':data_bloqueio', $this->data_bloqueio);
            $stmt->bindValue(':horario_inicio', $this->horario_inicio);
            $stmt->bindValue(':horario_fim', $this->horario_fim);
            return $stmt->execute();
        }

        public function deleteBloqueio(){
            $query = 'DELETE FROM tb_horarios_bloqueio WHERE id IN(:id)';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':id', $this->id);
            return $stmt->execute();
        }        
    }
?>