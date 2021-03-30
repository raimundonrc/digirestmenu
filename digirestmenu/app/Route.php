<?php
    namespace App;
    use RNF\Bootstrap;

    class Route extends Bootstrap{
        public function __construct(){
            $this->initRoutes();               
        }

        private function initRoutes(){//define as rotas
            /*index*/
            $route['index'] = [//Chama a página index
                'route' => '/cardapio',
                'controller' => 'Index',
                'action' => 'index'
            ];

            /*avaliações*/
            $route['avaliacao/estabelecimento/cadastra'] = [//cadastra cliente e avaliação do estabelecimento
                'route' => '/avaliacao/estabelecimento/cadastra',
                'controller' => 'Index',
                'action' => 'insertClienteAvaliacaoEstab'
            ];

            $route['/avaliacao/estabelecimento/avalia'] = [//Cadastra avaliação do estabelecimento
                'route' => '/avaliacao/estabelecimento/avalia',
                'controller' => 'Index',
                'action' => 'insertAvaliacaoEstab'
            ];

            $route['avaliacao/estabelecimento/procuracadastro'] = [//Procura cadastro do cliente
                'route' => '/avaliacao/estabelecimento/procuracadastro',
                'controller' => 'Index',
                'action' => 'searchClient'
            ];

            $route['/avaliacao/prato/avalia'] = [//Cadastra avaliação do estabelecimento
                'route' => '/avaliacao/prato/avalia',
                'controller' => 'Index',
                'action' => 'insertAvaliacaoPrato'
            ];

            $route['avaliacao/prato/cadastra'] = [//cadastra cliente e avaliação do estabelecimento
                'route' => '/avaliacao/prato/cadastra',
                'controller' => 'Index',
                'action' => 'insertClienteAvaliacaoPrato'
            ];


            /*reservas*/
            $route['reservas'] = [//Chama a página index de reservas
                'route' => '/reservas',
                'controller' => 'Reservas',
                'action' => 'index'
            ];

            $route['reservas/inicio'] = [//Chama a página inicio de reservas
                'route' => '/reservas/inicio',
                'controller' => 'Reservas',
                'action' => 'inicio'
            ];

            $route['reservas/procuracadastro'] = [//Verifica o cadastro do cliente 
                'route' => '/reservas/procuracadastro',
                'controller' => 'Reservas',
                'action' => 'procuraCadastro'
            ];

            $route['reservas/cadastracliente'] = [//Faz o cadastro do cliente 
                'route' => '/reservas/cadastracliente',
                'controller' => 'Reservas',
                'action' => 'cadastraCliente'
            ];

            $route['reservas/cadastrareserva'] = [//Faz o cadastro da reserva
                'route' => '/reservas/cadastrareserva',
                'controller' => 'Reservas',
                'action' => 'cadastraReserva'
            ];

            $route['reservas/buscahorarios'] = [//Retornar os horários da data
                'route' => '/reservas/buscahorarios',
                'controller' => 'Reservas',
                'action' => 'getHorarios'
            ];

            /*painel*/
            $route['painel/logout'] = [//Faz o logout
                'route' => '/painel/logout',
                'controller' => 'Painel',
                'action' => 'logout'
            ];

            $route['painel'] = [//Chama a página painel ou login
                'route' => '/painel',
                'controller' => 'Painel',
                'action' => 'painel'
            ];

            $route['painel/dashboard'] = [//chama a pagina dashboard de painel
                'route' => '/painel/dashboard',
                'controller' => 'Painel',
                'action' => 'dashboard'
            ];

            $route['painel/avaliacoesEstabelecimento'] = [ //chama a pagina avaliacoesEstabelecimento de painel
                'route' => '/painel/avaliacoes/estabelecimento',
                'controller' => 'Painel',
                'action' => 'avaliacoesEstabelecimento'
            ];

            $route['painel/avaliacoesProdutos'] = [ //chama a pagina avaliacoesProdutos de painel
                'route' => '/painel/avaliacoes/produtos',
                'controller' => 'Painel',
                'action' => 'avaliacoesProdutos'
            ];

            $route['painel/mediaAvaliacoesProdutos'] = [ //chama a pagina avaliacoesProdutos de painel
                'route' => '/painel/avaliacoes/mediaprodutos',
                'controller' => 'Painel',
                'action' => 'mediaAvaliacoesProdutos'
            ];

            $route['painel/reserva'] = [ //chama a pagina reserva de painel
                'route' => '/painel/reserva',
                'controller' => 'Painel',
                'action' => 'reserva'
            ];

            $route['painel/subgrupos'] = [ //chama a pagina subgrupos de painel
                'route' => '/painel/subgrupos',
                'controller' => 'Painel',
                'action' => 'subgrupos'
            ];

            $route['painel/itens'] = [//chama a pagina itens de painel
                'route' => '/painel/itens',
                'controller' => 'Painel',
                'action' => 'itens'
            ];

            $route['painel/estabelecimento'] = [//chama a página de estabelecimento do painel
                'route' => '/painel/estabelecimento',
                'controller' => 'Painel',
                'action' => 'estabelecimento'
            ];
            
            $route['painel/reserva/status'] = [//altera o status da reserva
                'route' => '/painel/reserva/status',
                'controller' => 'Painel',
                'action' => 'setStatusReserva'
            ];

            $route['painel/reserva/altera'] = [//altera os dados da reserva
                'route' => '/painel/reserva/altera',
                'controller' => 'Painel',
                'action' => 'updateReserva'
            ];

            $route['painel/subgrupos/insere'] = [//insere subgrupo
                'route' => '/painel/subgrupos/insere',
                'controller' => 'Painel',
                'action' => 'insertSubgrupo'
            ];

            $route['painel/subgrupo/ordena'] = [//altera ordem do subgrupo
                'route' => '/painel/subgrupo/ordena',
                'controller' => 'Painel',
                'action' => 'setOrdens'
            ];

            $route['painel/subgrupo/altera'] = [//altera um subgrupo
                'route' => '/painel/subgrupo/altera',
                'controller' => 'Painel',
                'action' => 'setSubgrupo'
            ];

            $route['painel/item/insere'] = [//insere um item
                'route' => '/painel/item/insere',
                'controller' => 'Painel',
                'action' => 'insertItem'
            ];

            $route['painel/item/altera'] = [//altera um item
                'route' => '/painel/item/altera',
                'controller' => 'Painel',
                'action' => 'setItem'
            ];

            $route['painel/item/deletaFoto'] = [//deleta a imagem de um item
                'route' => '/painel/item/foto/deleta',
                'controller' => 'Painel',
                'action' => 'deleteFoto'
            ];

            $route['painel/item/alteraFoto'] = [//altera a imagem de um item
                'route' => '/painel/item/foto/altera',
                'controller' => 'Painel',
                'action' => 'setFoto'
            ];

            $route['painel/item/deleta'] = [//deleta um item
                'route' => '/painel/item/deleta',
                'controller' => 'Painel',
                'action' => 'deleteItem'
            ];

            $route['painel/estabelecimento/alteraLogotipo'] = [//altera o logotipo do estabelecimento
                'route' => '/painel/item/logotipo/altera',
                'controller' => 'Painel',
                'action' => 'setLogotipo'
            ]; 

            $route['painel/estabelecimento/alteraNome'] = [//altera o nome do estabelecimento
                'route' => '/painel/item/nome/altera',
                'controller' => 'Painel',
                'action' => 'setNome'
            ]; 

            $route['painel/estabelecimento/alteraWhatsapp'] = [//altera o whatsapp
                'route' => '/painel/item/whatsapp/altera',
                'controller' => 'Painel',
                'action' => 'setWhatsapp'
            ];  

            $route['painel/estabelecimento/alteraInstagram'] = [//altera o instagram
                'route' => '/painel/item/instagram/altera',
                'controller' => 'Painel',
                'action' => 'setInstagram'
            ];   

            $route['painel/estabelecimento/alteraFacebook'] = [//altera o facebook
                'route' => '/painel/item/facebook/altera',
                'controller' => 'Painel',
                'action' => 'setFacebook'
            ];  

            $route['painel/estabelecimento/alteraTwitter'] = [//altera o twitter
                'route' => '/painel/item/twitter/altera',
                'controller' => 'Painel',
                'action' => 'setTwitter'
            ];    

            $route['painel/estabelecimento/alteraTripadvisor'] = [//altera o tripadvisor
                'route' => '/painel/item/tripadvisor/altera',
                'controller' => 'Painel',
                'action' => 'setTripadvisor'
            ]; 
            
            $route['painel/reservas/buscahorarios'] = [//Retornar os horários da data
                'route' => '/painel/reservas/buscahorarios',
                'controller' => 'Painel',
                'action' => 'getHorarios'
            ];

            $route['painel/reservas/cadastra'] = [//Cadastra uma reserva
                'route' => '/painel/reservas/cadastra',
                'controller' => 'Painel',
                'action' => 'insertReserva'
            ];

            $route['painel/bloqueio/cadastra'] = [//Cadastra um bloqueio
                'route' => '/painel/bloqueio/cadastra',
                'controller' => 'Painel',
                'action' => 'insertBloqueio'
            ];

            $route['painel/bloqueio/remove'] = [//Remove um bloqueio
                'route' => '/painel/bloqueio/remove',
                'controller' => 'Painel',
                'action' => 'deleteBloqueio'
            ];

            $route['painel/horarios/cadastra'] = [//Cadastra um horário
                'route' => '/painel/horarios/cadastra',
                'controller' => 'Painel',
                'action' => 'insertHorario'
            ];

            $route['painel/horarios/remove'] = [//Remove um horário
                'route' => '/painel/horarios/remove',
                'controller' => 'Painel',
                'action' => 'deleteHorario'
            ];

            $route['painel/itens/options'] = [//Chama a view de <options> dos itens
                'route' => '/painel/itens/options',
                'controller' => 'Painel',
                'action' => 'getItensOption'
            ];

            $this->route = $route;
        }
    }
?>