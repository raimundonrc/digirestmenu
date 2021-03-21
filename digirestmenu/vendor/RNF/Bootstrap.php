<?php 
    namespace RNF;
    use RNF\Models\DigirestmenuClientes;

    Class Bootstrap{
        protected $route;

        protected function getClient(){//inicia um cliente
            $drmClient = new DigirestmenuClientes;

            $clientes = $drmClient->getAll();//Busca os clientes do banco

            $clientRoute =  explode('/', $this->getUri());//Pega a rota cliente na uri

            $cliente = '/' . $clientRoute[1];

            foreach($clientes as $cl){
                if($cl->rota_raiz == $cliente){//Verifica se a rota raiz do cliente é igual a rota da uri
                    session_start();
                    $_SESSION['client'] = $cl;//inicia uma sessão do cliente
                }
            }
        }

        private function getUri(){//retorna o path da URI
            $uri = explode('?', $_SERVER['REQUEST_URI']);            
            return $uri[0];
        }

        public function run(){//define o banco, chama o controller e o método
            $this->getClient();
            if(isset($_SESSION['client'])){
                foreach($this->route as $route){
                    if($_SESSION['client']->rota_raiz.$route['route'] === $this->getUri()){
                        //define o namespace do controller
                        $class = 'App\Controllers\\'.ucfirst($route['controller']).'Controller';
                        $action = $route['action'];//define a ação/método do controller
                        $controller = new $class;//instância o controller
                        $controller->$action();//chama o método do controller
                    } 
                }

                if(!isset($controller)){//Rota não encontrada.
                    echo '<h1>Página não encontrada.</h1>';
                }

            } else {
                echo '<h1>Página não encontrada.</h1>';
            }
        }
    }
?>