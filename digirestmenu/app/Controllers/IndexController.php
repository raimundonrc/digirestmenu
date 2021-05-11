<?php
    namespace App\Controllers;
    use App\Models\Painel\Subgrupos;
    use App\Models\Painel\Itens;
    use App\Models\Painel\Estabelecimento;
    use App\Models\Painel\Clientes;
    use App\Models\Painel\AvaliacoesEstabelecimento;
    use App\Models\Painel\AvaliacaoPrato;

    class IndexController{        

        public function index(){//chama a página de cardápio
            $subgrupos = new Subgrupos;
            $itens = new Itens;
            $estabelecimento = new Estabelecimento();           

            $subgrupo = $subgrupos->getAllActive();
            $item = $itens->getAllActive();
            $itensDestaque = $itens->getDestaques();
            $estab = $estabelecimento->getAll();
            
            require '../digirestmenu/app/Views/Index/index.phtml';
        }

        public function insertClienteAvaliacaoEstab(){
            if(!(isset($_POST['client']) && $_POST['client'] != '' && isset($_POST['aval']) && $_POST['aval'] != '')){
                echo 'Dados incorretos.';
                return false;
            }

            $client = json_decode($_POST['client']);
            $avaliacao = json_decode($_POST['aval']);
            $clientes = new Clientes;
            $avalEstab = new AvaliacoesEstabelecimento;
            
            $clientes->__set('nome', $client->nomeCliente);
            $clientes->__set('email', $client->email);
            $clientes->__set('telefone', $client->telefone); 
            $cl = $clientes->getByEmail();
            if(!$cl) {//Cliente ainda não está cadastrado
                if($clientes->insertClient()){//sucesso
                    $clientes->__set('email', $client->email);
                    $last = $clientes->getByEmail();
                    $avalEstab->__set('id_cliente', $last->id);
                    $avalEstab->__set('nota_avaliacao', $avaliacao->nota);
                    $avaliacao->comentarioRestaurante != '' ? 
                        $avalEstab->__set('comentario', $avaliacao->comentarioRestaurante) : 
                        $avalEstab->__set('comentario', null);  
                    $avalEstab->insertAvaliacao();
                    echo json_encode(["status" => "success"]);                    
                    return true;
                } else {
                    echo json_encode(["status" => "fail"]);
                    return false;
                }
            } else {// cliente já está cadastrado                
                echo json_encode(["status" => "fail"]);
                return false;
            }
        }

        public function insertClienteAvaliacaoPrato(){
            if(!(isset($_POST['client']) && $_POST['client'] != '' && isset($_POST['aval']) && $_POST['aval'] != '')){
                echo 'Dados incorretos.';
                return false;
            }

            $client = json_decode($_POST['client']);
            $avaliacao = json_decode($_POST['aval']);

            $clientes = new Clientes;
            $avalPrato = new AvaliacaoPrato;
            
            $clientes->__set('nome', $client->nomeCliente);
            $clientes->__set('email', $client->email);
            $clientes->__set('telefone', $client->telefone);  
            $cl = $clientes->getByEmail();
            if(!$cl) {//Cliente ainda não está cadastrado
                if($clientes->insertClient()){
                    $clientes->__set('email', $client->email);
                    $last = $clientes->getByEmail();                
                    $avalPrato->__set('id_cliente', $last->id);
                    $avalPrato->__set('id_item', $avaliacao->id);
                    $avalPrato->__set('nota_avaliacao', $avaliacao->nota);
                    $avaliacao->comentarioPrato != '' ? $avalPrato->__set('comentario', $avaliacao->comentarioPrato) : $avalPrato->__set('comentario', null); 
                    $avalPrato->insertAvaliacao();
                    echo json_encode(["status" => "success"]);                    
                    return true;
                } else {
                    echo json_encode(["status" => "fail"]);
                return false;
                }
            } else {// cliente já está cadastrado                
                echo json_encode(["status" => "fail"]);
                return false;
            }
        }

        public function insertAvaliacaoEstab(){
            if(!(isset($_POST['client']) && $_POST['client'] != '' && isset($_POST['aval']) && $_POST['aval'] != '')){
                echo 'Dados incorretos.';
                return false;
            }

            $client = json_decode($_POST['client']);
            $avaliacao = json_decode($_POST['aval']);

            $avalEstab = new AvaliacoesEstabelecimento;
            $avalEstab->__set('id_cliente', $client->id);
            $avalEstab->__set('nota_avaliacao', $avaliacao->nota);
            $avaliacao->comentarioRestaurante != '' ? $avalEstab->__set('comentario', $avaliacao->comentarioRestaurante) : $avalEstab->__set('comentario', null);  
            $avalEstab->insertAvaliacao();
        }

        public function insertAvaliacaoPrato(){
            if(!(isset($_POST['client']) && $_POST['client'] != '' && isset($_POST['aval']) && $_POST['aval'] != '')){
                echo 'Dados incorretos.';
                return false;
            }

            $client = json_decode($_POST['client']);
            $avaliacao = json_decode($_POST['aval']);

            $avalPrato = new AvaliacaoPrato;
            $clientes = new Clientes;
            $avalPrato->__set('id_cliente', $client->id);
            $avalPrato->__set('id_item', $avaliacao->id);
            $avalPrato->__set('nota_avaliacao', $avaliacao->nota);
            $avaliacao->comentarioPrato != '' ? $avalPrato->__set('comentario', $avaliacao->comentarioPrato) : $avalPrato->__set('comentario', null);  
            $avalPrato->insertAvaliacao();
        }

        public function searchClient(){
            $validEmail = $_POST['email'] && $_POST['email'] ==! '';

            if(!$validEmail){
                echo 'Erro. Parâmetro e-mail inválido.';
                return false;
            }

            $clientes = new Clientes;
            $clientes->__set('email', $_POST['email']);
            $cl = $clientes->getByEmail();
            echo json_encode($cl);
        }
    }
?>