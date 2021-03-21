<?php
    namespace App\Controllers;
    use App\Models\Painel\Reservas;
    use App\Models\Painel\AvaliacoesEstabelecimento;
    use App\Models\Painel\Subgrupos;  
    use App\Models\Painel\Itens;  
    use App\Models\Painel\Estabelecimento;
    use App\Models\Painel\HorariosReserva;
    use App\Models\Painel\HorariosBloqueio;

    class PainelController{

        public function painel(){//Chama a página painel ou login      

            $estabelecimento =  new Estabelecimento;

            //Verifica se está logado. Se não estiver inicia uma sessão de login
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                
                //Verifica se os POSTs estão setados
                if(isset($_POST['email']) && $_POST['email'] != '' && isset($_POST['password']) && $_POST['password'] != ''){
                    $estabelecimento->__set('email',  $_POST['email']);
                    $estabelecimento->__set('senha',  md5($_POST['password']));

                    if($estabelecimento->login()){//tenta o login
                        //inicia a sessão
                        $_SESSION['rota_raiz'] = $estabelecimento->login()->rota_raiz;

                    } else {//login incorreto
                        $login = false;
                        require '../digirestmenu/app/Views/Painel/index.phtml';  
                        return false;
                    }
                    
                } else {//POSTs não estão setados
                    require '../digirestmenu/app/Views/Painel/index.phtml'; 
                    return false;  
                }
            }

            $estab = $estabelecimento->getAll();
            //Define a rota a ser chamado via ajax
            $_SESSION['route'] = isset($_SESSION['route']) ? $_SESSION['route'] : $_SESSION['client']->rota_raiz.'/painel/reserva';
            require '../digirestmenu/app/Views/Painel/painel.phtml';        
        }

        public function logout(){
            
            session_destroy();

            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function dashboard(){//chama a página de dashboard
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $reservas = new Reservas;
            $avalEstab = new AvaliacoesEstabelecimento;
            $reservas->__set('data_reserva', /* Date('Y-m-d') */'2020-11-17');
            $reserva = $reservas->getAll();

            require '../digirestmenu/app/Views/Painel/dashboard.phtml';
        }

        public function avaliacoesEstabelecimento(){//Chama a página de avaliações do estabelecimento
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            
            $avalEstab = new avaliacoesEstabelecimento;
            $average = $avalEstab->getAverage();

            if($_POST){
                //Validar os dados
                $validDataInicio = isset($_POST['data_inicio']) && $_POST['data_inicio'] != '' ? true : false;
                $validDataFim = isset($_POST['data_fim']) && $_POST['data_fim'] != '' ? true : false;
                $validOrganizar = isset($_POST['organizar']) && $_POST['organizar'] != '' ? true : false;
                $validIntervalo = isset($_POST['data_inicio']) && isset($_POST['data_fim']) && Date($_POST['data_inicio']) <= Date($_POST['data_fim']) ? true : false;

                if(!($validDataInicio && $validDataFim && $validOrganizar && $validIntervalo)){
                    echo '<h4 style="color: #ff0000">Dados incorretos. Por gentileza, verifique os dados e tente novamente.</h4>';
                    require_once '../digirestmenu/app/Views/Painel/avaliacoesEstabelecimento.phtml';
                    return false;
                }
                $avaliacoes = $avalEstab->getByDate($_POST['data_inicio'], $_POST['data_fim'], $_POST['organizar']);
            }            

            if($_GET){
                $avaliacoes = $avalEstab->getByDate(Date('Y-m-d'), Date('Y-m-d'), 'recente');
            } 

            require_once '../digirestmenu/app/Views/Painel/avaliacoesEstabelecimento.phtml';
        }

        public function avaliacoesProdutos(){//Chama a página de avaliações dos produtos
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }
            require '../digirestmenu/app/Views/Painel/avaliacoesProdutos.phtml';
        }

        public function reserva(){            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $uri = explode('?', $_SERVER['REQUEST_URI']);
            if(isset($uri[1])){
                $get = explode('=', $uri[1]);
                $_SESSION['data'] = $get[1];
            }  

            $reservas = new Reservas;
            $hora = new HorariosReserva;
            $bloq = new HorariosBloqueio;
            
            if(isset($_SESSION['data'])){  
                $diaSemana= Date('w', strtotime($_SESSION['data']));
                
                $reservas->__set('data_reserva', $_SESSION['data']);

            } else {
                $diaSemana = Date('w');
                $reservas->__set('data_reserva', Date('Y-m-d'));
            }

            isset($_SESSION['data']) ? $bloq->__set('data_bloqueio', $_SESSION['data']) : $bloq->__set('data_bloqueio', Date('Y-m-d'));

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
            $reserva = $reservas->getAll();
            $horario = $hora->getAll();
            $bloqueio = $bloq->getAll();
            $intervaloBloq = $bloq->getIntervalo();
            require '../digirestmenu/app/Views/Painel/reservas.phtml';
        }

        public function subgrupos(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }
            $subgrupos = new Subgrupos;
            $subgrupo = $subgrupos->getAll();
            require '../digirestmenu/app/Views/Painel/subgrupos.phtml';
        }

        public function itens(){ 
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }  

            $subgrupos = new Subgrupos;
            $itens = new Itens;

            $subgrupo = $subgrupos->getAll();

            $requestUri = explode('?', $_SERVER['REQUEST_URI']);

            if(isset($requestUri[1])){
                $idSubgrupo = explode('=', $requestUri[1]);
                $itens->__set('id_subgrupo', $idSubgrupo[1]);

            } else if(count($subgrupo) > 0){
                $itens->__set('id_subgrupo', $subgrupo[0]->id);
            }

            $item = $itens->getBySubgrupo();

            require '../digirestmenu/app/Views/Painel/itens.phtml';
        }

        public function estabelecimento(){            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }
            $estabelecimento = new Estabelecimento;
            $hora = new HorariosReserva;
            $horario = $hora->getIntervalo();
            $estab = $estabelecimento->getAll();

            require '../digirestmenu/app/Views/Painel/estabelecimento.phtml';
        }

        public function insertReserva(){
            $validNome = isset($_POST['add-nome']) && strlen($_POST['add-nome']) > 2 ? true : false;
            $validTel = isset($_POST['add-telefone']) && strlen($_POST['add-telefone']) > 13 ? true : false;
            $validQtd = isset($_POST['add-qtdPessoas']) && $_POST['add-qtdPessoas'] > 0 ? true : false;
            $validData = isset($_POST['add-dataReserva']) && Date($_POST['add-dataReserva']) >= Date('Y-m-d') ? true : false;
            $validHorario = isset($_POST['add-horarioReserva']) && Date($_POST['add-dataReserva'].' '.$_POST['add-horarioReserva']) > Date('Y-m-d H:i') ? true : false;

            if($validNome && $validTel && $validTel && $validData && $validHorario){
                $reserva = new Reservas;
                $reserva->__set('nome', $_POST['add-nome']);
                $reserva->__set('telefone', $_POST['add-telefone']);
                $reserva->__set('qtd_pessoas', $_POST['add-qtdPessoas']);
                $reserva->__set('data_reserva', $_POST['add-dataReserva']);
                $reserva->__set('hora_reserva', $_POST['add-horarioReserva']);
                $_POST['add-observacao'] ? $reserva->__set('horario_reserva', $_POST['add-observacao']) : $reserva->__set('horario_reserva', null);
                $reserva->__set('id_cliente', null);
                $reserva->__set('email', null);
                if($reserva->insertReserva()){
                    $_SESSION['data'] = Date($_POST['add-dataReserva']);
                    $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/reserva';
                    $_SESSION['insert']  = true;
                    header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                }

            } else {
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/reserva';
                $_SESSION['insert']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
            }
        }

        public function setStatusReserva(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }
            $reservas = new Reservas;
            $reservas->__set('id', $_POST['id']);
            $reservas->__set('status_reserva', strtoupper($_POST['status']));
            $reservas->setStatus();
            $dataArray = explode('/', $_POST['data']);
            $_SESSION['data'] = $dataArray[2].'-'.$dataArray[1].'-'.$dataArray[0];
            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/reserva';
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function updateReserva(){   
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            //validações
            $validNome  = isset($_POST['nome']) && strlen($_POST['nome']) > 2 ? true : false; 
            $validTelefone  = isset($_POST['telefone']) && strlen($_POST['telefone']) > 9  ? true : false;
            $validQtdPessoas  = isset($_POST['qtdPessoas']) && floor($_POST['qtdPessoas']) > 0  ? true : false;
            $validData  = isset($_POST['data'])  && $_POST['data'] != '';
            $validHorario = isset($_POST['horario']) && $_POST['horario'] != '' && Date($_POST['data'].' '.$_POST['horario']) >= Date('Y-m-d H:i') ? true : false;

            if( $validNome && $validTelefone && $validQtdPessoas && $validData && $validHorario){     
                $reservas = new Reservas;           
                $reservas->__set('id', $_POST['id']);
                $reservas->__set('nome', $_POST['nome']);
                $reservas->__set('telefone', $_POST['telefone']);
                $_POST['email'] != '' ? $reservas->__set('email', $_POST['email']) : $reservas->__set('email', null);
                $reservas->__set('qtd_pessoas', floor($_POST['qtdPessoas']));
                $reservas->__set('data_reserva', $_POST['data']);
                $reservas->__set('hora_reserva', $_POST['horario']);
                $_POST['obs'] != '' ? $reservas->__set('observacao', $_POST['obs']) : $reservas->__set('observacao', null);
                $reservas->setReserva();
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/reserva';
                $_SESSION['update']  = true;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');

            } else {
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/reserva';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
            }
        }

        public function insertSubgrupo(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }
            //Validação
            $validDescricao = isset($_POST['descricao']) && $_POST['descricao'] != '' ? true : false;
            
            if($validDescricao){
                $subgrupos = new Subgrupos;
                $subgrupos->__set('descricao', ucfirst((strtolower($_POST['descricao']))));
                $subgrupos->insertSubgrupo();
                
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/subgrupos'; 
                $_SESSION['insert']  = true; 

            }else{
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/subgrupos';
                $_SESSION['insert']  = false;
            }

            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function setOrdens(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $request = explode('?', $_SERVER['REQUEST_URI']);
            $params = explode('&', $request[1]);
            $id = explode('=', $params[0]);
            $ordem = explode('=', $params[1]);

            //Validação
            $validId = $id[1] ? true : false;
            $validOrdem = $ordem[1] ? true : false;

            if($validId && $validOrdem){
                $subgrupos = new Subgrupos;
                $subgrupos->__set('id', $id[1]);
                $subgrupos->__set('ordem', $ordem[1]);
                $subgrupos->setOrdens();

                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/subgrupos';
                $_SESSION['update']  = true;

            } else {
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/subgrupos';  
                $_SESSION['update']  = false;             
            }
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function setSubgrupo(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }
            //Validação
            $validId = isset($_POST['id']) && $_POST['id'] != '' ? true : false;
            $validDescricao = isset($_POST['descricao']) && $_POST['descricao'] != '' ? true : false;
            $validAtivo = isset($_POST['ativo']) && $_POST['ativo'] != '' ? true : false;

            if($validId && $validDescricao && $validAtivo){
                $subgrupos = new Subgrupos;
                $subgrupos->__set('id', $_POST['id']);
                $subgrupos->__set('descricao', ucfirst((strtolower($_POST['descricao']))));
                $subgrupos->__set('ativo', $_POST['ativo']);
                $subgrupos->setSubgrupo();
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/subgrupos';
                $_SESSION['update']  = true;

            } else {                
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/subgrupos?update=false';
                $_SESSION['update']  = false;
            }            
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function insertItem(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $itens = new Itens;

            //Validações
            $validNome = isset($_POST['nome']) && $_POST['nome'] != '' ? true : false;
            $validPreco = isset($_POST['preco']) && $_POST['preco'] != '' &&  $_POST['preco'] > 0 ? 
                true : false;
            
            if(isset($_POST['subgrupo']) && $_POST['subgrupo'] != ''){
                $validSubgrupo = true;
                $itens->__set('id_subgrupo', $_POST['subgrupo']);

            } else{
                $validSubgrupo = false;
                $itens->__set('id_subgrupo', '');
            }

            if($validNome && $validPreco && $validSubgrupo){  
                $itens->__set('nome', ucfirst((strtolower($_POST['nome']))));
                isset($_POST['descricao']) && $_POST['descricao'] != '' ? 
                    $itens->__set('descricao', $_POST['descricao']) : $itens->__set('descricao', null);
                $itens->__set('preco', $_POST['preco']);               
                isset($_POST['ativo']) && $_POST['ativo'] != '' ? 
                    $itens->__set('ativo', $_POST['ativo']) : $itens->__set('ativo', true);

                if(isset($_FILES['foto']['name']) && $_FILES['foto']['error'] === 0){
                    $extension = pathinfo ( $_FILES['foto']['name'], PATHINFO_EXTENSION );//extrai a extensão
    
                    if(strstr ( '.jpg;.jpeg;.gif;.png', $extension)){//Testa se a extensão é aceitável
                        $routeClient = str_replace('/', '', $_SESSION['client']->rota_raiz);
                        //define um novo nome com a extensão
                        $newFileName = $routeClient.'-'.time().'.'.$extension;
                        $pathDestiny = 'img/'.$newFileName;
    
                        if(move_uploaded_file($_FILES['foto']['tmp_name'], $pathDestiny)) {
                            $itens->__set('foto', $newFileName);

                        }else{//Erro ao mover a imagem
                            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens/?idSubgrupo='.$itens->__get('id_subgrupo');
                            $_SESSION['insert']  = false;
                            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                            return false;
                        }
    
                    } else{//Extensão não aceita
                        $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                        $_SESSION['insert']  = false;
                        header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                        return false;
                    }                    
    
                } else {//Nenhuma foto foi enviada
                    $itens->__set('foto', null);
                }

                if($itens->insertItem()){
                    $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                    $_SESSION['insert']  = true;
                }
                
            } else {//dados inválidos
               $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo'); 
               $_SESSION['insert']  = false;
            }
            
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
            
        }

        public function setItem(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $itens = new Itens;

            //Validações
            $validId = isset($_POST['id']) && $_POST['id'] != '' ? true : false;
            $validNome = isset($_POST['nome']) && $_POST['nome'] != '' ? true : false;
            //$validFotoAtual = isset($_POST['fotoAtual']) && $_POST['fotoAtual'] != '' ? true : false;
            $validPreco = isset($_POST['preco']) && $_POST['preco'] != '' &&  $_POST['preco'] > 0 ? 
                true : false;

            if(isset($_POST['subgrupo']) && $_POST['subgrupo'] != ''){
                $validSubgrupo = true;
                $itens->__set('id_subgrupo', $_POST['subgrupo']);
                
            } else {
                $validSubgrupo = false;
                $itens->__set('id_subgrupo', '');
            }

            if($validNome && $validPreco && $validSubgrupo && $validId){
                $itens->__set('id', $_POST['id']);
                $itens->__set('nome', ucfirst((strtolower($_POST['nome']))));
                isset($_POST['descricao']) && $_POST['descricao'] != '' ? 
                    $itens->__set('descricao', $_POST['descricao']) : $itens->__set('descricao', null);
                $itens->__set('preco', $_POST['preco']); 

                isset($_POST['destaque']) && $_POST['destaque'] != '' ? 
                    $itens->__set('destaque', true) : $itens->__set('destaque', false);

                isset($_POST['ativo']) && $_POST['ativo'] != '' ? 
                    $itens->__set('ativo', $_POST['ativo']) : $itens->__set('ativo', true); 

                if($itens->setItem()){
                    $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                    $_SESSION['update']  = true;

                } else {
                    $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                    $_SESSION['update']  = false;
                }
                
            } else {//dados inválidos
               $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo'); 
               $_SESSION['update']  = false;
            }
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function deleteFoto(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $itens = new Itens;

            //validações
            $validId = isset($_POST['idItem']) && isset($_POST['idItem']) != '' ? true : false;
            $validFoto = isset($_POST['path-foto']) && isset($_POST['path-foto']) != '' ? true : false;
            if(isset($_POST['idSubgrupo']) && isset($_POST['idSubgrupo']) != ''){
                $validIdSubgrupo = true;
                $itens->__set('id_subgrupo', $_POST['idSubgrupo']);

            } else {
                $validIdSubgrupo = false;
                $itens->__set('id_subgrupo', '');
            }

            if($validId && $validFoto && $validIdSubgrupo){

                if(unlink($_POST['path-foto'])){                    
                    $itens->__set('id', $_POST['idItem']);
                    

                    if($itens->deleteFoto()){
                        $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                        $_SESSION['update']  = true;
                    } 
                }

            } else {
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                $_SESSION['update']  = false;
            }
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');

        }

        public function setFoto(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $itens = new Itens;

            $validId = isset($_POST['idItem']) && $_POST['idItem'] != '' ? true : false;
            $validFoto = isset($_FILES['foto']['name']) && $_FILES['foto']['error'] === 0 ? true : false;

            if(isset($_POST['idSubgrupo']) && isset($_POST['idSubgrupo']) != ''){
                $validIdSubgrupo = true;
                $itens->__set('id_subgrupo', $_POST['idSubgrupo']);

            } else {
                $validIdSubgrupo = false;
                $itens->__set('id_subgrupo', '');
            }

            if($validId && $validFoto && $validIdSubgrupo){//dados válidos
                //extrai a extensão   
                $extension = pathinfo ( $_FILES['foto']['name'], PATHINFO_EXTENSION );

                if(strstr ( '.jpg;.jpeg;.gif;.png', $extension)){//Testa se a extensão é aceitável
                    $routeClient = str_replace('/', '', $_SESSION['client']->rota_raiz);
                    //define um novo nome com a extensão
                    $newFileName = $routeClient.'-'.time().'.'.$extension;
                    $pathDestiny = 'img/'.$newFileName;//Path de destino

                    //move o arquivo para a pasta no server
                    if(move_uploaded_file($_FILES['foto']['tmp_name'], $pathDestiny)) {
                        $itens->__set('foto', $newFileName);
                        $itens->__set('id', $_POST['idItem']);

                        if(isset($_POST['path-foto']) && $_POST['path-foto'] != ''){
                            unlink($_POST['path-foto']);//Se houver foto anterior, será excluída
                        }

                        $itens->setFoto();

                        //sucesso no update
                        $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                        $_SESSION['update']  = true;     

                    }else{//Erro ao mover a imagem
                        $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                        $_SESSION['update']  = false;
                        header('Location:'.$_SESSION['client']->rota_raiz.'/painel');

                        return false;
                    }

                    

                } else {//Extensão não aceita
                    $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                    $_SESSION['update']  = false;
                    header('Location:'.$_SESSION['client']->rota_raiz.'/painel');

                    return false;
                }

            } else {//dados são inválidos
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                $_SESSION['update']  = false;
            }
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function deleteItem(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $itens = new Itens;

            $validId = isset($_POST['idItem']) && $_POST['idItem'] != '' ? true : false;

            if(isset($_POST['idSubgrupo']) && isset($_POST['idSubgrupo']) != ''){
                $validIdSubgrupo = true;
                $itens->__set('id_subgrupo', $_POST['idSubgrupo']);

            } else {
                $validIdSubgrupo = false;
                $itens->__set('id_subgrupo', '');
            }

            if($validId && $validIdSubgrupo){//dados válidos
                $itens->__set('id', $_POST['idItem']);

                if(isset($_POST['pathFoto']) && $_POST['pathFoto'] != ''){
                    unlink($_POST['pathFoto']); //Excluindo a foto
                }                    

                $itens->deleteItem();

                //Sucesso no delete
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                $_SESSION['delete']  = true;

            } else {//Dados inválidos
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/itens?idSubgrupo='.$itens->__get('id_subgrupo');
                $_SESSION['delete']  = false;
            } 
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function setLogotipo(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento = new Estabelecimento;

            if($_POST['id'] == '' || $_POST['id'] == null || !isset($_POST['id'])){
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }  

            if(isset($_FILES['fotoLogo']['name']) && $_FILES['fotoLogo']['error'] === 0){
                $extension = pathinfo ( $_FILES['fotoLogo']['name'], PATHINFO_EXTENSION );//extrai a extensão

                if(strstr ( '.jpg;.jpeg;.gif;.png', $extension)){//Testa se a extensão é aceitável
                    $routeClient = str_replace('/', '', $_SESSION['client']->rota_raiz);
                    //define um novo nome com a extensão
                    $newFileName = $routeClient.'-'.time().'.'.$extension;
                    $pathDestiny = 'img/'.$newFileName;

                    if(move_uploaded_file($_FILES['fotoLogo']['tmp_name'], $pathDestiny)) { 
                        $estabelecimento->__set('id', $_POST['id']);

                        $logo = $estabelecimento->getLogotipo();//Verifica e busca logotipo atual.
                        
                        if($logo){//exclui a logo atual
                            unlink('img/'.$logo);
                        }
                        
                        $estabelecimento->__set('foto_logo', $newFileName);

                        $estabelecimento->setLogotipo();

                        $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                        $_SESSION['update']  = true;
                        header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                        return true;


                    }else{//Erro ao mover a imagem
                        $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                        $_SESSION['update']  = false;
                        header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                        return false;
                    }

                } else{//Extensão não aceita
                    $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                    $_SESSION['update']  = false;
                    header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                    return false;
                }                    

            } else {//Nenhuma foto foi enviada
                $itens->__set('foto', null);
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }
           
        }

        public function setNome(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento = new Estabelecimento;

            if(!($_POST['id'] && $_POST['estabelecimento'])){
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento->__set('id', $_POST['id']);
            $estabelecimento->__set('estabelecimento', $_POST['estabelecimento']);
            $estabelecimento->setNome();

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
            $_SESSION['update']  = true;
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function setWhatsapp(){

            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento = new Estabelecimento;
            
            if(!$_POST['id']){
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento->__set('id', $_POST['id']);
            $estabelecimento->__set('whatsapp',  $_POST['whatsapp']);
            $estabelecimento->setWhatsapp();

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
            $_SESSION['update']  = true;
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
            
            

            
        }

        public function setInstagram(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento = new Estabelecimento;
            
            if(!$_POST['id']){
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento->__set('id', $_POST['id']);
            $estabelecimento->__set('instagram', $_POST['instagram']);
            $estabelecimento->setInstagram();

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
            $_SESSION['update']  = true;
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function setFacebook(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento = new Estabelecimento;

            if(!$_POST['id']){
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento->__set('id', $_POST['id']);
            $estabelecimento->__set('facebook', $_POST['facebook']);
            $estabelecimento->setFacebook();

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
            $_SESSION['update']  = true;
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function setTwitter(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento = new Estabelecimento;

            if(!$_POST['id']){
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento->__set('id', $_POST['id']);
            $estabelecimento->__set('twitter', $_POST['twitter']);
            $estabelecimento->setTwitter();

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
            $_SESSION['update']  = true;
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function setTripadvisor(){
            
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento = new Estabelecimento;

            if(!$_POST['id']){
                $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
                $_SESSION['update']  = false;
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $estabelecimento->__set('id', $_POST['id']);
            $estabelecimento->__set('tripadvisor', $_POST['tripadvisor']);
            $estabelecimento->setTripadvisor();

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';
            $_SESSION['update']  = true;
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        //retorna um html com tags <option>. Ideal para ser chamado via ajax.
        public function getHorarios(){
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }
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
                require '../digirestmenu/app/Views/Painel/horariosReserva.phtml';
            } else {
                echo 'Data inválida';
            }
        }   
        
        public function insertBloqueio(){
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            //Validações
            $validData = isset($_POST['bloq-data']) && Date($_POST['bloq-data']) >= Date('Y-m-d') ? true : false;
            $validHorario = isset($_POST['horario-bloq-inicio']) && isset($_POST['horario-bloq-final']) && Date($_POST['horario-bloq-inicio']) <= Date($_POST['horario-bloq-final']) ? true : false;

            if($validData && $validHorario){
                $bloq = new HorariosBloqueio;
                $bloq->__set('data_bloqueio', $_POST['bloq-data']);
                $bloq->__set('horario_inicio', $_POST['horario-bloq-inicio']);
                $bloq->__set('horario_fim', $_POST['horario-bloq-final']);

                if($bloq->insertBloqueio()){
                    $_SESSION['insert']  = true;
                } else {
                    $_SESSION['insert']  = false;
                }
            } else {
                $_SESSION['insert']  = false;
            }
            
            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/reserva';            
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function deleteBloqueio(){
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $validId = isset($_POST['id_bloq']) && $_POST['id_bloq'] != '' ? true : false;

            if( $validId){
                $bloq = new HorariosBloqueio;
                $bloq->__set('id', $_POST['id_bloq']);

                if($bloq->deleteBloqueio()){
                    $_SESSION['delete']  = true;
                    
                } else {
                    $_SESSION['delete']  = false;
                }

            } else {
                $_SESSION['delete']  = false;
            }

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/reserva';            
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        } 
        
        public function insertHorario(){
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $validHorario = isset($_POST['horaInicio']) && isset($_POST['horaFim']) && Date($_POST['horaInicio']) <= Date($_POST['horaInicio']) ? true : false;
            $validDiaSemana = isset($_POST['diaSemana']) && $_POST['diaSemana'] != '' ? true : false;

            if($validHorario && $validDiaSemana){
                $horarios = new HorariosReserva;
                $horarios->__set('horario_inicio', $_POST['horaInicio']);
                $horarios->__set('horario_fim', $_POST['horaFim']);
                $horarios->__set('dia_da_semana', $_POST['diaSemana']);

                if($horarios->insertHorario()){
                    $_SESSION['insert'] = true;
                }

            } else {
                $_SESSION['insert'] = false;
            }

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';            
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }

        public function deleteHorario(){
            //verifica se está logado
            if(!(isset($_SESSION['rota_raiz']) && $_SESSION['rota_raiz'] === $_SESSION['client']->rota_raiz)){
                header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
                return false;
            }

            $validId = isset($_POST['id-horario']) && $_POST['id-horario'] != '' ? true : false;

            if($validId){
                $horarios = new HorariosReserva;
                $horarios->__set('id', $_POST['id-horario']);
                if($horarios->deleteHorario()){
                    $_SESSION['delete'] = true;
                }

            } else {
                $_SESSION['delete'] = false;
            }

            $_SESSION['route'] = $_SESSION['client']->rota_raiz.'/painel/estabelecimento';            
            header('Location:'.$_SESSION['client']->rota_raiz.'/painel');
        }
    }
?>