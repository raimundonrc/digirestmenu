<?php
    namespace App\Controllers;
    use App\Models\Painel\Estabelecimento;
    use App\Models\Painel\Clientes;
    use App\Models\Painel\Reservas;
    use App\Models\Painel\HorariosReserva;
    use App\Models\Painel\HorariosBloqueio;

    class ReservasController{
        public function index(){//Chama a página index
            header('Location:'.$_SESSION['client']->rota_raiz.'/reservas/inicio');
        }

        public function header(){
            $estab = new Estabelecimento;
            $estabelecimento = $estab->getEstabelecimentoByRoute();
            require '../digirestmenu/app/Views/Reservas/header.phtml';
        }
        
        public function inicio(){//Chama a página inicio
            $this->header();            
            require '../digirestmenu/app/Views/Reservas/index.phtml';
        }

        public function procuraCadastro(){
            if(!$_POST){
               $this->index();
            }
            $this->header();

            $cliente =  new Clientes;            
            $cliente->__set('email', $_POST['email']);
            $cl = $cliente->getByEmail();

            if($cl){
                $hora = new HorariosReserva;
                $bloq = new HorariosBloqueio;
                $diaSemana = Date('w');
                switch($diaSemana){
                    case 0:
                        $diaSemana = 'dom';
                        break;
                    case 1:
                        $diaSemana = 'seg';
                        break;
                    case 2:
                        $diaSemana = 'ter';
                        break;
                    case 3:
                        $diaSemana = 'qua';
                        break;
                    case 4:
                        $diaSemana = 'qui';
                        break;
                    case 5:
                        $diaSemana = 'sex';
                        break;
                    case 6:
                        $diaSemana = 'sab';
                        break;
                }
                $hora->__set('dia_da_semana', $diaSemana);
                $bloq->__set('data_bloqueio', Date('Y-m-d'));
                $horario = $hora->getAll();
                $bloqueio = $bloq->getAll();
                require '../digirestmenu/app/Views/Reservas/reserva.phtml'; 

            } else {
                require '../digirestmenu/app/Views/Reservas/cadastro.phtml';
            }
        }

        public function cadastraCliente(){
            if(!$_POST){
                $this->index();
            }
            $this->header();

            //Validações
            $validNome = isset($_POST['nome']) && strlen($_POST['nome']) > 2 ? true : false;
            $validTelefone = isset($_POST['telefone']) && strlen($_POST['telefone']) > 13 ? true : false;
            $validEmail = isset($_POST['nome']) ? true : false;

            if(!($validNome && $validTelefone && $validEmail)){
                $this->index();
            }
            
            $cliente =  new Clientes;
            $cliente->__set('nome', $_POST['nome']);
            $cliente->__set('telefone', $_POST['telefone']);
            $cliente->__set('email', $_POST['email']);


            if($cliente->insertClient()){
                $hora = new HorariosReserva;
                $bloq = new HorariosBloqueio;
                $diaSemana = Date('w');
                switch($diaSemana){
                    case 0:
                        $diaSemana = 'dom';
                        break;
                    case 1:
                        $diaSemana = 'seg';
                        break;
                    case 2:
                        $diaSemana = 'ter';
                        break;
                    case 3:
                        $diaSemana = 'qua';
                        break;
                    case 4:
                        $diaSemana = 'qui';
                        break;
                    case 5:
                        $diaSemana = 'sex';
                        break;
                    case 6:
                        $diaSemana = 'sab';
                        break;
                }
                $hora->__set('dia_da_semana', $diaSemana);
                $bloq->__set('data_bloqueio', Date('Y-m-d'));
                $horario = $hora->getAll();
                $bloqueio = $bloq->getAll();
                $cl = $cliente->getByEmail();
                require '../digirestmenu/app/Views/Reservas/reserva.phtml';

            } else {
                echo '<p style="text-align: center; color: red; margin: 0 10px;">Desculpe, não foi possível fazer sua reserva. Por gentileza, ligue para o Restaurante e solicite sua reserva.<p>';
            }
        }

        public function cadastraReserva(){   
            if(!$_POST){
                $this->index();
            }
            $this->header();
            //Validações         
            $validId = isset($_POST['id_cliente']) ? true : false;
            $validNome = isset($_POST['nome']) && strlen($_POST['nome']) > 2 ? true : false;
            $validTel = isset($_POST['telefone']) && strlen($_POST['telefone']) > 13 ? true : false;
            $validEmail = isset($_POST['email']) ? true : false;
            $validQtd = isset($_POST['qtdPessoas']) && $_POST['qtdPessoas'] > 0 ? true : false;
            $validData = isset($_POST['data']) && strtotime($_POST['data']) >= strtotime(Date('Y-m-d'))? true : false;
            $validHora = isset($_POST['horario']) && strtotime($_POST['data'] . ' ' . $_POST['horario']) >= strtotime(Date('Y-m-d H:i:s')) ? true : false;

            if($validId && $validNome && $validTel && $validEmail && $validQtd && $validData && $validHora){
                $reserva = new Reservas;
                $reserva->__set('id_cliente', $_POST['id_cliente']);
                $reserva->__set('nome', $_POST['nome']);
                $reserva->__set('telefone', $_POST['telefone']);
                $reserva->__set('email', $_POST['email']);
                $reserva->__set('qtd_pessoas', $_POST['qtdPessoas']);
                $reserva->__set('data_reserva', $_POST['data']);
                $reserva->__set('hora_reserva', $_POST['horario']);
                $_POST['observacao'] != '' ? $reserva->__set('observacao', $_POST['observacao']) : $reserva->__set('observacao', null);
                if($reserva->insertReserva()){                   
                    require '../digirestmenu/app/Views/Reservas/reservaSucesso.phtml';

                } else {
                    echo '<h5 class="text-danger text-center p-3">Desculpe, não foi possível fazer sua reserva. Por gentileza, ligue para o Restaurante e solicite sua reserva.<h5>';
                }
            } else {
                echo '<h5 class="text-danger text-center p-3">Desculpe, não foi possível fazer sua reserva. Verifique se a data e horário estão corretos.<h5>';
                echo '
                    <div class="d-flex justify-content-center">
                        <a href="'.$_SESSION['client']->rota_raiz.'/reservas/inicio" class="btn btn-primary">
                        Início
                        </a>
                    </div>
                ';
            }
        }

        public function getHorarios(){
            $validDate = isset($_POST['date']) && $_POST['date'] != '' ? true : false;
            if($validDate){
                $hora = new HorariosReserva;
                $bloq = new HorariosBloqueio;
                $diaSemana= Date('w', strtotime($_POST['date']));
                switch($diaSemana){
                    case 0:
                        $diaSemana = 'dom';
                        break;
                    case 1:
                        $diaSemana = 'seg';
                        break;
                    case 2:
                        $diaSemana = 'ter';
                        break;
                    case 3:
                        $diaSemana = 'qua';
                        break;
                    case 4:
                        $diaSemana = 'qui';
                        break;
                    case 5:
                        $diaSemana = 'sex';
                        break;
                    case 6:
                        $diaSemana = 'sab';
                        break;
                }
                $hora->__set('dia_da_semana', $diaSemana);
                $bloq->__set('data_bloqueio', $_POST['date']);
                $horario = $hora->getAll();
                $bloqueio = $bloq->getAll();
                require '../digirestmenu/app/Views/Reservas/horariosReserva.phtml';
            } else {
                echo 'Data inválida';
            }
        }
    }
?>