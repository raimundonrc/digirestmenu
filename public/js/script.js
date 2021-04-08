class AvaliacaoPrato{
    constructor(){
        this.id = null;
        this.nota = null;
        this.comentarioPrato = null;
    }
}

class AvaliacaoRestaurante{
    constructor(){
        this.nota = null;
        this.comentarioRestaurante = null;
    }
}

class Cliente{
    constructor(){
        this.id = null;
        this.nomeCliente = null;
        this.email = null;
        this.telefone = null;
    }
}

let avaliacaoPrato = new AvaliacaoPrato();
let avaliacaoRestaurante = new AvaliacaoRestaurante();
let cliente = new Cliente();

window.onload = ()=>{
    //Percorre os icones da estrelas e adiciona os eventos
    for(let i = 1; i <= 5; i++){
        let star = document.querySelector(`#starEstab-${i}`);//Seleciona os icones 
        let starPrato = document.querySelector(`#starPrato-${i}`);//Seleciona os icones 

        //ALtera o ícone das estrelas de avaliação do estabelecimento no mouseover
        star.addEventListener('mouseover', ()=>{
            if(avaliacaoRestaurante.nota != null){
                return false;
            }
            let id = star.dataset.id;//Pega o data-id do icone                
            for(let icover = id; icover > 0; icover--){//Percorre os IDs e altera os ícones.
                let elementStar = document.querySelector(`#starEstab-${icover}`);
                elementStar.classList.add('fa-star');  
                elementStar.classList.remove('fa-star-o');
            }  
        });

        //ALtera o ícone das estrelas de avaliação do prato no mouseover
        starPrato.addEventListener('mouseover', ()=>{
            if(avaliacaoPrato.nota != null){
                return false;
            }
            let id = starPrato.dataset.id;//Pega o data-id do icone                
            for(let icover = id; icover > 0; icover--){//Percorre os IDs e altera os ícones.
                let elementStar = document.querySelector(`#starPrato-${icover}`);
                elementStar.classList.add('fa-star');  
                elementStar.classList.remove('fa-star-o');
            }  
        });

        //ALtera o ícone das estrelas de avaliação do estabelecimento no mouseout
        star.addEventListener('mouseout', ()=>{
            if(avaliacaoRestaurante.nota !== null){
                return false;
            }
            for(let icout = 1; icout <= 5; icout++){//Percorre os IDs e altera os ícones.
                let elementStar = document.querySelector(`#starEstab-${icout}`);
                elementStar.classList.add('fa-star-o');
                elementStar.classList.remove('fa-star');
            }
        });

        //ALtera o ícone das estrelas de avaliação do prato no mouseout
        starPrato.addEventListener('mouseout', ()=>{
            if(avaliacaoPrato.nota !== null){
                return false;
            }
            for(let icout = 1; icout <= 5; icout++){//Percorre os IDs e altera os ícones.
                let elementStar = document.querySelector(`#starPrato-${icout}`);
                elementStar.classList.add('fa-star-o');
                elementStar.classList.remove('fa-star');
            }
        });

        star.addEventListener('click', ()=>{//Adiciona o evento click da estrelas de avaliação do estabelecimento
            for(let icout = 1; icout <= 5; icout++){//Percorre os IDs e altera os ícones.
                let elementStar = document.querySelector(`#starEstab-${icout}`);
                elementStar.classList.add('fa-star-o');
                elementStar.classList.remove('fa-star');
            }

            let id = star.dataset.id;                
            for(let icover = id; icover > 0; icover--){//Percorre os IDs e altera os ícones.
                let elementStar = document.querySelector(`#starEstab-${icover}`);
                if(elementStar.classList.contains('fa-star-o')){
                    elementStar.classList.add('fa-star');  
                    elementStar.classList.remove('fa-star-o');
                }                
            } 

            avaliacaoRestaurante.nota = star.dataset.id;//Seta o valor da nota

            if(avaliacaoRestaurante.nota != null){
                document.querySelector('#btnAvaliarRest').disabled = false;
            }
        });

        starPrato.addEventListener('click', ()=>{//Adiciona o evento click da estrelas de avaliação do prato
            for(let icout = 1; icout <= 5; icout++){//Percorre os IDs e altera os ícones.
                let elementStar = document.querySelector(`#starPrato-${icout}`);
                elementStar.classList.add('fa-star-o');
                elementStar.classList.remove('fa-star');
            }

            let id = starPrato.dataset.id;                
            for(let icover = id; icover > 0; icover--){//Percorre os IDs e altera os ícones.
                let elementStar = document.querySelector(`#starPrato-${icover}`);
                if(elementStar.classList.contains('fa-star-o')){
                    elementStar.classList.add('fa-star');  
                    elementStar.classList.remove('fa-star-o');
                }                
            } 

            avaliacaoPrato.nota = id;//Seta o valor da nota

            if(avaliacaoPrato.nota != null){
                let btnAvalPrato= document.querySelector('#btnAvaliarPrato');
                btnAvalPrato.disabled = false;
                btnAvalPrato.style.cursor = 'pointer';
            }
        });
    }

    //Evento de do botão que abre o modal de avaliação do restaurante
    let btnAvalEstab = document.querySelector('#btn-avaliacao-estab');
    btnAvalEstab.addEventListener('click', ()=>{
        clearModalsEstab();
        let cl = localStorage.getItem('_cliente_');
        if(cl){
            let url = btnAvalEstab.dataset.url;
            let dados = {"email": cl};
            $.post(url, dados, function(response, status){//Enviando os dados via post
                if(status === 'success'){//Sucesso
                    let client = JSON.parse(response);                        
                    if(client){
                        cliente.id = client.id;
                        cliente.nomeCliente = client.nome;
                        cliente.email = client.email;
                        cliente.telefone = client.telefone;
                        document.querySelector('#avaliando-como').innerHTML = `Você está avaliando como <strong>${cliente.nomeCliente}</strong>.`;
                        document.querySelector('#btn-nao-sou').innerHTML = `Não é <strong>${cliente.nomeCliente}</strong>?`;
                    } 
                }
            }).fail(function(jqXHR, textStatus, msg){//Erro.
                $('#modal-erro').modal('show'); //Chama o modal de erro
            });
        }
        
        $('#modal-avaliacao-restaurante').modal('show');            
    });

    //Evento do botão que exclui o cliente do local storage no modal avaliação restaurante
    document.querySelector('#btn-nao-sou').addEventListener('click', ()=>{
        cliente.id = null;
        cliente.nomeCliente = null;
        cliente.email = null;
        cliente.telefone = null;
        document.querySelector('#avaliando-como').innerHTML = '';
        document.querySelector('#btn-nao-sou').innerHTML ='';
        localStorage.removeItem('_cliente_');
    });

    //Evento do botão que exclui o cliente do local storage no modal avaliação prato
    document.querySelector('#btn-prato-nao-sou').addEventListener('click', ()=>{
        cliente.id = null;
        cliente.nomeCliente = null;
        cliente.email = null;
        cliente.telefone = null;
        document.querySelector('#avaliando-prato-como').innerHTML = '';
        document.querySelector('#btn-prato-nao-sou').innerHTML ='';
        localStorage.removeItem('_cliente_');
    });

    //Evento do botão de avaliação do modal de avaliação do restaurante
    document.querySelector('#btnAvaliarRest').addEventListener('click', ()=>{            
        let comentarioEstab = document.querySelector('#comentarioRestaurante');
        let inputEmail = document.querySelector('#email-consulta');//Input modal email consulta
        let error = document.querySelector('#erro-email-consulta');//mensagem de erro do email

        inputEmail.value = '';
        error.innerHTML = '';

        inputEmail.classList.remove('valid');
        inputEmail.classList.remove('is-valid');

        avaliacaoRestaurante.comentarioRestaurante = comentarioEstab.value.length > 1 ? comentarioEstab.value : null;

        if(cliente.id != null){
            enviarDadosAvaliacao();
        } else {
            $('#modal-email-estab').modal('show');
        }

        $('#modal-email-estab').modal('show');
        $('#modal-avaliacao-restaurante').modal('hide');
    });

    //Evento do botão de avaliação do modal avaliação prato
    document.querySelector('#btnAvaliarPrato').addEventListener('click', ()=>{            
        let comentarioPrato = document.querySelector('#comentarioPrato');
        let inputEmailPrato = document.querySelector('#email-prato-consulta');//Input modal email consulta
        let errorPrato = document.querySelector('#erro-email-prato-consulta');//mensagem de erro do email

        inputEmailPrato.value = '';
        errorPrato.innerHTML = '';

        inputEmailPrato.classList.remove('valid');
        inputEmailPrato.classList.remove('is-valid');

        avaliacaoPrato.comentarioPrato = comentarioPrato.value.length > 1 ? comentarioPrato.value : null;

        if(cliente.id != null){
            enviarAvaliacaoPrato();
        } else {
            $('#modal-email-prato').modal('show');
        }

        $('#modal-email-prato').modal('show');
        $('#modal-avaliacao-prato').modal('hide');
    });


    //Evento do botão de consulta do modal de e-mail após a avaliação do restaurante
    document.querySelector('#btn-consulta').addEventListener('click', ()=>{
        let inputEmail = document.querySelector('#email-consulta');
        let error = document.querySelector('#erro-email-consulta');

        let validEmail = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(inputEmail.value) && true;

        if(!validEmail){
            error.innerHTML = 'Por gentileza, informe um e-mail válido.';
            inputEmail.classList.add('is-invalid');
            inputEmail.classList.remove('is-valid');
        } else {
            error.innerHTML = '';
            inputEmail.classList.add('is-valid');
            inputEmail.classList.remove('is-invalid');
        }

        if(!validEmail){
            return false;
        }     
        
        let url = inputEmail.dataset.url;
        let dados = {"email": inputEmail.value};
        $.post(url, dados, function(response, status){//Enviando os dados via post
            if(status === 'success'){//Sucesso
                //$('#modal-sucesso-restaurante').modal('show');//Chama o modal de sucesso
                let cl = JSON.parse(response);
                if(cl){
                    cliente.id = cl.id;
                    cliente.email = cl.email;
                    enviarDadosAvaliacao();
                } else {
                    document.querySelector('#estab-email').value = inputEmail.value;
                    $('#modal-email-estab').modal('hide');
                    $('#modal-dados-cliente-estabelecimento').modal('show');
                }
            }
        })
    
        .fail(function(jqXHR, textStatus, msg){//Erro.
            $('#modal-erro').modal('show'); //Chama o modal de erro
        });

    });

    //Evento do botão de consulta do modal de e-mail após a avaliação do prato
    document.querySelector('#btn-consulta-prato').addEventListener('click', ()=>{
        let inputEmail = document.querySelector('#email-prato-consulta');
        let error = document.querySelector('#erro-email-prato-consulta');

        let validEmail = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(inputEmail.value) && true;

        if(!validEmail){
            error.innerHTML = 'Por gentileza, informe um e-mail válido.';
            inputEmail.classList.add('is-invalid');
            inputEmail.classList.remove('is-valid');
        } else {
            error.innerHTML = '';
            inputEmail.classList.add('is-valid');
            inputEmail.classList.remove('is-invalid');
        }

        if(!validEmail){
            return false;
        }     
        
        let url = inputEmail.dataset.url;
        let dados = {"email": inputEmail.value};
        $.post(url, dados, function(response, status){//Enviando os dados via post
            if(status === 'success'){//Sucesso
                //$('#modal-sucesso-restaurante').modal('show');//Chama o modal de sucesso
                let cl = JSON.parse(response);
                if(cl){
                    cliente.id = cl.id;
                    cliente.email = cl.email;
                    enviarAvaliacaoPrato();
                } else {
                    document.querySelector('#pratoEmail').value = inputEmail.value;
                    $('#modal-email-prato').modal('hide');
                    $('#modal-dados-cliente-prato').modal('show');
                }
            }
        })
    
        .fail(function(jqXHR, textStatus, msg){//Erro.
            $('#modal-erro').modal('show'); //Chama o modal de erro
        });

    });     
}

/* Menu de navegação e display dos grupos do cardápio*/
function exibir(id){//exibe a section do subgrupo chamado e oculta os demais
    let subgrupo = document.querySelectorAll('.__subgrupo');
    for(let s of subgrupo){
        s.classList.add('d-none'); //Add o display none na classe subgrupo.
    }
    document.querySelector(id).classList.remove('d-none'); //Remove o display none do id desejado.
}

function setPaginaAtiva(id){ //Altera a cor do link da página ativa, no menu de navegação.
    document.querySelector('.pagina-ativa').classList.remove('pagina-ativa');
    document.querySelector(id).classList.add('pagina-ativa');
}

function clearModalsEstab(){//Limpa todos os campos e anula ao objetos.
    //Anula os objetos

    avaliacaoRestaurante.nota = null;
    avaliacaoRestaurante.comentarioRestaurante = null; 

    cliente.id = null;
    cliente.nomeCliente = null;
    cliente.email = null;
    cliente.telefone = null;

    //Limpa as estrelas de avaliação do prato
    for(let icout = 1; icout <= 5; icout++){//Percorre os IDs e altera os ícones.
        let elementStar = document.querySelector(`#starEstab-${icout}`);
        elementStar.classList.add('fa-star-o');
        elementStar.classList.remove('fa-star');
    }

    document.querySelector('#comentarioRestaurante').value = ''; //Limpa o textarea

    document.querySelector('#btnAvaliarRest').disabled = true;//Desativa o botão Avaliar do modal restaurante.

    //Inputs
    let inputEstabNome = document.querySelector('#estab-nome');    
    let inputEstabEmail = document.querySelector('#estab-email');    
    let inputEstabTelefone = document.querySelector('#estab-telefone');

    //Inputs
    inputEstabNome.value = '';
    inputEstabEmail.value = '';
    inputEstabTelefone.value = '';
    
    //Remove as classes de validação
    inputEstabNome.classList.remove('is-valid');
    inputEstabNome.classList.remove('is-invalid');
    inputEstabEmail.classList.remove('is-valid');
    inputEstabEmail.classList.remove('is-invalid');
    inputEstabTelefone.classList.remove('is-valid');
    inputEstabTelefone.classList.remove('is-invalid');

    //Mensgens de erro
    document.querySelector('#erro-estab-nome').innerHTML = '';
    document.querySelector('#erro-estab-email').innerHTML = '';
    document.querySelector('#erro-estab-telefone').innerHTML = '';
}

function clearModalPrato(){//Limpa o modal de avaliação do prato.
    avaliacaoPrato.nota = null;
    avaliacaoPrato.comentarioPrato = null; 

    cliente.id = null;
    cliente.nomeCliente = null;
    cliente.email = null;
    cliente.telefone = null;

    //Limpa as estrelas de avaliação do prato
    for(let icout = 1; icout <= 5; icout++){//Percorre os IDs e altera os ícones.
        let elementStar = document.querySelector(`#starPrato-${icout}`);
        elementStar.classList.add('fa-star-o');
        elementStar.classList.remove('fa-star');
    }

    document.querySelector('#comentarioPrato').value = ''; //Limpa o textarea

    let btnAvalPrato = document.querySelector('#btnAvaliarPrato');
    btnAvalPrato.disabled = true;
    btnAvalPrato.style.cursor = 'default';

    //Inputs
    let inputPratoNome = document.querySelector('#pratoNome');    
    let inputPratoEmail = document.querySelector('#pratoEmail');    
    let inputPratoTelefone = document.querySelector('#pratoTelefone');

    //Inputs
    inputPratoNome.value = '';
    inputPratoEmail.value = '';
    inputPratoTelefone.value = '';
    
    //Remove as classes de validação
    inputPratoNome.classList.remove('is-valid');
    inputPratoNome.classList.remove('is-invalid');
    inputPratoEmail.classList.remove('is-valid');
    inputPratoEmail.classList.remove('is-invalid');
    inputPratoTelefone.classList.remove('is-valid');
    inputPratoTelefone.classList.remove('is-invalid');

    //Mensgens de erro
    document.querySelector('#erro-prato-nome').innerHTML = '';
    document.querySelector('#erro-prato-email').innerHTML = '';
    document.querySelector('#erro-prato-telefone').innerHTML = '';
}

//Insere o produto no modal
function addProdutoModal(nomeProduto, urlImg, idProduto){
    document.querySelector('#titulo-produto').innerHTML = nomeProduto;
    document.querySelector('#img-modal').src = urlImg;
    clearModalPrato();
    avaliacaoPrato.id = idProduto;
    let cl = localStorage.getItem('_cliente_');
        if(cl){
            let btnAvalPrato= document.querySelector('#btnAvaliarPrato');
            let url = btnAvalPrato.dataset.url;//url que busca o cliente no banco
            let dados = {"email": cl};
            $.post(url, dados, function(response, status){//Busca o cliente no banco
                if(status === 'success'){//Sucesso
                    let client = JSON.parse(response);                        
                    if(client){
                        cliente.id = client.id;
                        cliente.nomeCliente = client.nome;
                        cliente.email = client.email;
                        cliente.telefone = client.telefone;
                        document.querySelector('#avaliando-prato-como').innerHTML = `Você está avaliando como <strong>${cliente.nomeCliente}</strong>.`;
                        document.querySelector('#btn-prato-nao-sou').innerHTML = `Não é <strong>${cliente.nomeCliente}</strong>?`;
                    } 
                }
            }).fail(function(jqXHR, textStatus, msg){//Erro.
                $('#modal-erro').modal('show'); //Chama o modal de erro
                return false;
            });
        }
    $('#modal-avaliacao-prato').modal('show');
}

//Envia os dados de cadastro de cliente e avaliação restaurante
function enviarDadosEstabCadastro(url){ 
    document.querySelector('#email-exist-error').classList.add('d-none');//Oculta a mensagem de email já existe

    let inputEstabNome = document.querySelector('#estab-nome');    
    let inputEstabEmail = document.querySelector('#estab-email');    
    let inputEstabTelefone = document.querySelector('#estab-telefone');

    let estabNome = inputEstabNome.value;
    let estabEmail = inputEstabEmail.value;
    let estabTelefone = inputEstabTelefone.value;

    let erroEstabNome = document.querySelector('#erro-estab-nome');
    let erroEstabEmail = document.querySelector('#erro-estab-email');
    let erroEstabTelefone = document.querySelector('#erro-estab-telefone');

    //Validações
    let validNome = estabNome.length > 2 && true;
    let validEmail = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(estabEmail) && true;
    let validTelefone = estabTelefone.length >= 14 && estabTelefone.length < 16 && true;

    if(!validNome){        
        erroEstabNome.innerHTML = 'O campo nome precisa ter no mínimo 3 caracteres.';
        inputEstabNome.classList.add('is-invalid');
    } else {
        erroEstabNome.innerHTML = '';
        inputEstabNome.classList.add('is-valid');
        inputEstabNome.classList.remove('is-invalid');
    }

    if(!validEmail){        
        erroEstabEmail.innerHTML = 'Por gentileza, informe um e-mail válido';
        inputEstabEmail.classList.add('is-invalid');
    } else {
        erroEstabEmail.innerHTML = '';
        inputEstabEmail.classList.add('is-valid');
        inputEstabEmail.classList.remove('is-invalid');
    }

    if(!validTelefone){        
        erroEstabTelefone.innerHTML = 'Por gentileza, informe um telefone válido';
        inputEstabTelefone.classList.add('is-invalid');
    } else {
        erroEstabTelefone.innerHTML = '';
        inputEstabTelefone.classList.add('is-valid');
        inputEstabTelefone.classList.remove('is-invalid');
    }

    if(!(validNome && validEmail && validTelefone)){
        return false;
    }   

    cliente.nomeCliente = estabNome;
    cliente.email = estabEmail; 
    cliente.telefone = estabTelefone;
    
    //Trasforma em json
    let aval = JSON.stringify(avaliacaoRestaurante);;
    let client = JSON.stringify(cliente);
    let dados = {aval, client};

    $.post(url, dados, function(response, status){//Enviando os dados via post
        if(status === 'success'){//Sucesso
            let statusResponse;
            try{
                statusResponse = JSON.parse(response);
            }catch{
                $('#modal-erro').modal('show'); //Chama o modal de erro 
            }
            if(statusResponse.status == 'success'){//Email informado não está cadastrado
                localStorage.setItem('_cliente_', cliente.email);
                $('#modal-sucesso-restaurante').modal('show');//Chama o modal de sucesso
                $('#modal-dados-cliente-estabelecimento').modal('hide'); //Fecha o modal de input dos dados do cliente
            } else {//Email informado já está cadastrado
                document.querySelector('#email-exist-error').classList.remove('d-none');//exibe a mensagem de email já existe
            }
            console.log(response);
        }
    })
    .fail(function(jqXHR, textStatus, msg){//Erro.
        $('#modal-erro').modal('show'); //Chama o modal de erro
    });
    
}

//Envia os dados de cadastro de cliente e avaliação prato
function enviarDadosPratoCadastro(url){ 
    document.querySelector('#email-exist-error-prato').classList.add('d-none');//Oculta a mensagem de email já existe

    let inputPratoNome = document.querySelector('#pratoNome');    
    let inputPratoEmail = document.querySelector('#pratoEmail');    
    let inputPratoTelefone = document.querySelector('#pratoTelefone');

    let pratoNome = inputPratoNome.value;
    let pratoEmail = inputPratoEmail.value;
    let pratoTelefone = inputPratoTelefone.value;

    let erroPratoNome = document.querySelector('#erro-prato-nome');
    let erroPratoEmail = document.querySelector('#erro-prato-email');
    let erroPratoTelefone = document.querySelector('#erro-prato-telefone');

    //Validações
    let validNome = pratoNome.length > 2 && true;
    let validEmail = /[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(pratoEmail) && true;
    let validTelefone = pratoTelefone.length >= 14 && pratoTelefone.length < 16 && true;

    if(!validNome){        
        erroPratoNome.innerHTML = 'O campo nome precisa ter no mínimo 3 caracteres.';
        inputPratoNome.classList.add('is-invalid');
    } else {
        erroPratoNome.innerHTML = '';
        inputPratoNome.classList.add('is-valid');
        inputPratoNome.classList.remove('is-invalid');
    }

    if(!validEmail){        
        erroPratoEmail.innerHTML = 'Por gentileza, informe um e-mail válido';
        inputPratoEmail.classList.add('is-invalid');
    } else {
        erroPratoEmail.innerHTML = '';
        inputPratoEmail.classList.add('is-valid');
        inputPratoEmail.classList.remove('is-invalid');
    }

    if(!validTelefone){        
        erroPratoTelefone.innerHTML = 'Por gentileza, informe um telefone válido';
        inputPratoTelefone.classList.add('is-invalid');
    } else {
        erroPratoTelefone.innerHTML = '';
        inputPratoTelefone.classList.add('is-valid');
        inputPratoTelefone.classList.remove('is-invalid');
    }

    if(!(validNome && validEmail && validTelefone)){
        return false;
    }   

    cliente.nomeCliente = pratoNome;
    cliente.email = pratoEmail; 
    cliente.telefone = pratoTelefone;
    
    //Trasforma em json
    let aval = JSON.stringify(avaliacaoPrato);;
    let client = JSON.stringify(cliente);
    let dados = {aval, client};    

    $.post(url, dados, function(response, status){//Enviando os dados via post
        if(status === 'success'){//Sucesso
            let statusResponse;
            try{
                statusResponse = JSON.parse(response);
            }catch{
                $('#modal-erro').modal('show'); //Chama o modal de erro 
            }
            if(statusResponse.status == 'success'){//Email informado não está cadastrado
                localStorage.setItem('_cliente_', cliente.email);
                $('#modal-dados-cliente-prato').modal('hide'); //Fecha o modal de input dos dados do cliente
                $('#modal-sucesso-restaurante').modal('show');//Chama o modal de sucesso
            } else {//Email informado já está cadastrado
                document.querySelector('#email-exist-error-prato').classList.remove('d-none');//exibe a mensagem de email já existe
            }
            console.log(response);
        }
    })

    .fail(function(jqXHR, textStatus, msg){//Erro.
        $('#modal-erro').modal('show'); //Chama o modal de erro
    });   
}

function enviarDadosAvaliacao(){
    let url = document.querySelector('#btn-consulta').dataset.url;
    let aval = JSON.stringify(avaliacaoRestaurante);
    let client = JSON.stringify(cliente);
    let dados = {aval, client};

    $.post(url, dados, function(response, status){//Enviando os dados via post
        if(status === 'success'){//Sucesso
            localStorage.setItem('_cliente_', cliente.email);
            $('#modal-sucesso-restaurante').modal('show');//Chama o modal de sucesso
            $('#modal-email-estab').modal('hide');
            console.log(response);
        }
    })
    .fail(function(jqXHR, textStatus, msg){//Erro.
        $('#modal-erro').modal('show'); //Chama o modal de erro
        $('#modal-email-estab').modal('hide');
    });
}

function enviarAvaliacaoPrato(){
    let url = document.querySelector('#btn-consulta-prato').dataset.url;
    let aval = JSON.stringify(avaliacaoPrato);
    let client = JSON.stringify(cliente);
    let dados = {aval, client};

    $.post(url, dados, function(response, status){//Enviando os dados via post
        if(status === 'success'){//Sucesso
            localStorage.setItem('_cliente_', cliente.email);
            $('#modal-sucesso-restaurante').modal('show');//Chama o modal de sucesso
            $('#modal-email-prato').modal('hide');
            console.log(response);
        }
    })

    .fail(function(jqXHR, textStatus, msg){//Erro.
        $('#modal-erro').modal('show'); //Chama o modal de erro
        $('#modal-email-estab').modal('hide');
    });
}

function scrollHeader(idButton){//Movimenta o menu quando clicado
    let offset =  document.querySelector(`#${idButton}`).offsetLeft;
    document.querySelector('nav').scrollTo(offset - 100, 0);
}

function setBg(id, path){//Adiciona os itens no carrousel
    let carouselItem = document.querySelector(id);
    carouselItem.style.background = `url(${path})`;
    carouselItem.style.backgroundPosition = 'center';
    carouselItem.style.backgroundRepeat = 'no-repeat';
    carouselItem.style.backgroundSize = 'cover';
}