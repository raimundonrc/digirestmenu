<?php 
    namespace App\Models\Painel;
    use RNF\Connection\Connection;

    class HorariosReserva{
        private $id;
        private $horario_inicio;
        private $horario_fim;
        private $dia_da_semana;
        private $data_criacao;
        private $data_update;

        public function __get($attr){
            return $this->$attr;
        }

        public function __set($attr, $value){
            $this->$attr = $value;
        }

        public function getAll(){
            $query = 'SELECT id, DATE_FORMAT(horario_inicio, "%H:%i") AS horario_inicio,  DATE_FORMAT(horario_fim, "%H:%i") AS horario_fim, data_criacao, dia_da_semana, data_update FROM tb_horarios_reserva WHERE dia_da_semana IN(:dia_da_semana) ORDER BY horario_inicio';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue(':dia_da_semana', $this->dia_da_semana);
            $stmt->execute();
            $horario = $stmt->fetchAll(\PDO::FETCH_OBJ);
            $hor = Date('00:00');
            $horarios = array();
            foreach($horario as $hora){
                $ini= explode(':', $hora->horario_inicio);
                $fim= explode(':', $hora->horario_fim);
                $hIni = $ini[0];
                $hFim = $fim[0];
                $mIni = $ini[1];
                $mFim = $fim[1];

                if($hora->horario_inicio == $hora->horario_fim){
                    array_push($horarios, $hora->horario_inicio);
                }
                
                while($hIni <= $hFim){ //Cria as horas do intervalo                      
                    if($mIni > 59){
                        $mIni = 0;
                    }
                    
                    while($mIni <= 59){  //Cria os minutos do interval 
                        if($hor >= $hora->horario_fim) {
                            break;
                        }                          
                        $hor = Date(str_pad($hIni, 2, '0', STR_PAD_LEFT).':'.str_pad($mIni, 2, '0', STR_PAD_LEFT));//Cria os horários do intervalo
                        array_push($horarios, $hor);//Adiciona o horário ao array                        
                        $mIni += 15;
                    }                    
                    $hIni += 1;
                }
            }

            if(count($horarios) > 0){
                return array_unique($horarios);//Exclui itens repetidos no array

            } else {
                for($h = 0; $h <= 23; $h++){
                    for($m = 0; $m <= 59; $m += 15){ 
                            $horaReserva = Date(str_pad($h, 2, 0, STR_PAD_LEFT) .':'. str_pad($m, 2, 0, STR_PAD_LEFT));
                            array_push($horarios, $horaReserva);
                    }
                } 
                return array_unique($horarios);
            }
        }

        public function getIntervalo(){
            $query = 'SELECT id, DATE_FORMAT(horario_inicio, "%H:%i") AS horario_inicio,  DATE_FORMAT(horario_fim, "%H:%i") AS horario_fim, dia_da_semana, data_criacao, data_update FROM tb_horarios_reserva ORDER BY horario_inicio';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }

        public function insertHorario(){
            $query = 'INSERT INTO tb_horarios_reserva (horario_inicio, horario_fim, dia_da_semana) VALUES (:horario_inicio, :horario_fim, :dia_da_semana)';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue('horario_inicio', $this->horario_inicio);
            $stmt->bindValue('horario_fim', $this->horario_fim);
            $stmt->bindValue('dia_da_semana', $this->dia_da_semana);
            return $stmt->execute();
        }

        public function deleteHorario(){
            $query = 'DELETE FROM tb_horarios_reserva WHERE id IN(:id)';

            $con = new Connection;
            $stmt = $con->connect()->prepare($query);
            $stmt->bindValue('id', $this->id);
            return $stmt->execute();
        }
    }
?>