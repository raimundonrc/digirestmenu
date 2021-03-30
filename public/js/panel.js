function searchAvalEstabByDate(){//Busca as avaliações do estabelecimento
    //Evento do botão de pesquisa de avaliação estabelecimento
    let btnSearchAvalEstab = document.querySelector('#btn-search-avalEstab');
    
    //Inputs
    let dataInicio = document.querySelector('#avalEstab-data-inicio');
    let dataFim = document.querySelector('#avalEstab-data-fim');
    let organizar = document.querySelector('#avalEstab-organizar');

    //Validações
    let validInicio = dataInicio.value != '';
    let validFim = dataFim.value != '';
    let validIntervalo = dataInicio.value <= dataFim.value;
    //Mensagens de erro
    document.querySelector('#erro-data-inicio').innerHTML = !validInicio ? 'Data início inválida.' : '';
    document.querySelector('#erro-data-fim').innerHTML = !validFim ? 'Data fim inválida.' : '';
    document.querySelector('#erro-datas').innerHTML = !validIntervalo ? 'A data início tem que ser menor ou igual a data final.' : '';
    //Se houver erro, finaliza a função
    if(!validInicio || !validInicio || !validIntervalo) return false;

    
    let conteudo = document.querySelector('#conteudo');

    if(document.querySelector('#img-loading')){
        conteudo.removeChild(document.querySelector('#img-loading'));
    }
    let url = btnSearchAvalEstab.dataset.url;
    let data = {"data_inicio": dataInicio.value, "data_fim": dataFim.value, "organizar": organizar.value};
    let img = document.createElement('img');
    img.id="img-loading";
    img.height = '20';
    img.src = '../img/loading.gif';
    img.className = 'img-loading';        

    conteudo.prepend(img);
    $.post(url, data, (response, status)=>{
        
        if(status === 'success'){
            conteudo.innerHTML = response;
        }
    })
    .fail(function(jqXHR, textStatus, msg){//Erro.
        $('#modal-erro-dados').modal('show');//Chama o modal de erro
    });
}

function searchAvalProdByDate(){//Busca as avaliações de produtos
    //Evento do botão de pesquisa de avaliação de produtos
    let btnSearchAvalProd = document.querySelector('#btn-search-avalProd');
    
    //Inputs
    let dataInicio = document.querySelector('#avalProd-data-inicio');
    let dataFim = document.querySelector('#avalProd-data-fim');
    let subgrupo = document.querySelector('#avalProd-subgrupo');
    let produto = document.querySelector('#avalProd-produto');
    let organizar = document.querySelector('#avalProd-organizar');

    //Validações
    let validInicio = dataInicio.value != '';
    let validFim = dataFim.value != '';
    let validIntervalo = dataInicio.value <= dataFim.value;
    //Mensagens de erro
    document.querySelector('#erro-data-inicio').innerHTML = !validInicio ? 'Data início inválida.' : '';
    document.querySelector('#erro-data-fim').innerHTML = !validFim ? 'Data fim inválida.' : '';
    document.querySelector('#erro-datas').innerHTML = !validIntervalo ? 'A data início tem que ser menor ou igual a data final.' : '';
    //Se houver erro, finaliza a função
    if(!validInicio || !validInicio || !validIntervalo) return false;
    
    let conteudo = document.querySelector('#conteudo');

    if(document.querySelector('#img-loading')){
        conteudo.removeChild(document.querySelector('#img-loading'));
    }
    let url = btnSearchAvalProd.dataset.url;
    let data = {
        "data_inicio": dataInicio.value, 
        "data_fim": dataFim.value, 
        "subgrupo": subgrupo.value, 
        "produto": produto.value, 
        "organizar": organizar.value
    };
    let img = document.createElement('img');
    img.id="img-loading";
    img.height = '20';
    img.src = '../img/loading.gif';
    img.className = 'img-loading';        

    conteudo.prepend(img);
    $.post(url, data, (response, status)=>{
        
        if(status === 'success'){
            conteudo.innerHTML = response;
            getItensSelect(produto.value);           
        }
    })
    .fail(function(jqXHR, textStatus, msg){//Erro.
        $('#modal-erro-dados').modal('show');//Chama o modal de erro
    });
}
function searchAvalMediaProd(){//Busca as avaliações de produtos
    //Evento do botão de pesquisa de avaliação de produtos
    let btnSearchAvalProd = document.querySelector('#btn-search-avalProd');
    
    //Inputs
    let subgrupo = document.querySelector('#avalProd-subgrupo');
    let produto = document.querySelector('#avalProd-produto');
    
    let conteudo = document.querySelector('#conteudo');

    if(document.querySelector('#img-loading')){
        conteudo.removeChild(document.querySelector('#img-loading'));
    }
    let url = btnSearchAvalProd.dataset.url;
    let data = {
        "subgrupo": subgrupo.value, 
        "produto": produto.value
    };
    let img = document.createElement('img');
    img.id="img-loading";
    img.height = '20';
    img.src = '../img/loading.gif';
    img.className = 'img-loading';        

    conteudo.prepend(img);
    $.post(url, data, (response, status)=>{
        
        if(status === 'success'){
            conteudo.innerHTML = response;
            getItensSelect(produto.value);           
        }
    })
    .fail(function(jqXHR, textStatus, msg){//Erro.
        $('#modal-erro-dados').modal('show');//Chama o modal de erro
    });
}

function abrirModalAddReserva(url, date, id){//ALtera o texto do botão ver mais/ver menos
    getHorarios(url, date, id);
   
    document.querySelector('#data-modal-add').value = date;
    $('#modal-add-reserva').modal('show');
}

//Altera o conteudo do modal dos dados da reserva e exibe-o.
function abrirModalDadosReserva(nome, tel, qtdPessoas, data, horario, obs = '', email = ''){

    document.querySelector('#dado-nome').innerHTML = nome;
    document.querySelector('#dado-tel').innerHTML = tel;
    document.querySelector('#dado-qtdPessoas').innerHTML = `${qtdPessoas} Pessoa(s)`;
    document.querySelector('#dado-data').innerHTML = data;
    document.querySelector('#dado-horario').innerHTML = horario;    
    document.querySelector('#dado-obs').innerHTML = obs != '' ? `Obs: "${obs}"` : '';
    document.querySelector('#dado-email').innerHTML = email != '' ? email : '';

    $('#modal-dados-reserva').modal('show'); //Chama o modal dadosReserva
}

//Altera o conteudo do modal de alteração da reserva e exibe-o.
function abrirModalAlteracaoReserva(id, nome, tel, qtdPessoas, data, horario, obs = '', url){
    getHorarios(url, data);
    document.querySelector('#alterar_id').value = id;
    document.querySelector('#alterar-nome').value = nome;
    document.querySelector('#telefone').value = tel;
    document.querySelector('#alterar-qtdPessoas').value = qtdPessoas;
    document.querySelector('#alterar-data').value = data;
    
    document.querySelector('#alterar-observacao').value = obs;
    setTimeout(()=>{
        document.querySelector('#alterar-horario').value = horario;
        let altHorario = document.querySelectorAll('.altHorario');
        for(let h of altHorario){
           if(h.value == horario){
               h.disabled = false;
               h.innerHTML = horario;
           }
        }
    }, '100');

    $('#modal-alterar-reserva').modal('show');
}

function loadPage(url){//Carrega a página via ajax
    let conteudo = document.querySelector('#conteudo');

    if(document.querySelector('#img-loading')){
        conteudo.removeChild(document.querySelector('#img-loading'));
    }
    
    let img = document.createElement('img');
    img.id="img-loading";
    img.height = '20';
    img.src = '../img/loading.gif';
    img.className = 'img-loading';
    

    conteudo.prepend(img);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open('get', url);

    xmlHttp.onreadystatechange = () => {
        if(xmlHttp.readyState === 4 && xmlHttp.status === 200){
            conteudo.innerHTML = xmlHttp.response;
            $('#submenu-avaliacao-horizontal, #menu-horizontal-links, #submenu-config-cardapio-horizontal, #submenu-config-empresa-horizontal').collapse('hide');//Esconde o menu de links horizontal
        }

        if(xmlHttp.readyState === 4 && xmlHttp.status === 404){
        conteudo.innerHTML = '<h2 class="text-center text-secondary display-4">Desculpe, página não encontrada!</h2>';
        }
    }

    xmlHttp.send();
}


let intervalAlert = setInterval(() => {//some os alerts
    alert = document.querySelector('.alert');
    if(alert != null){
        alert.classList.add('d-none');
        if(alert.classList.contains('d-none')){
            clearInterval(intervalAlert);
        }
    }
}, 5000);

//Altera o conteudo do modal de alteração do subgrupo e exibe-o.
function abrirModalAlteraSubgrupo(id, descricao, ativo){
    document.querySelector('#txt-header-altera-subgrupo').innerHTML = `Alterar - ${descricao}`;
    document.querySelector('#subgrupo-id').value = id;
    document.querySelector('#altera-descricao').value = descricao;    
    ativo ? document.querySelector('#altera-ativo').checked = true : 
        document.querySelector('#altera-ativo').checked = false;
    !ativo ? document.querySelector('#altera-inativo').checked = true : 
        document.querySelector('#altera-inativo').checked;

    $('#modal-altera-subgrupo').modal('show');
}

function sendUrl(url){//Envia uma url via get
    window.location.href = url;
}

/* Altera o conteudo do modal de alteração do item e exibe-o. Também altera o modal de exclusão da foto do item e do modal de alteracao da foto do item. */
function abrirModalAlteraItem(id, nome, descricao, preco, id_subgrupo, foto, destaque, ativo){
    //Alterando o conteúdo do modal de alteração do item
    document.querySelector('#item-id').value = id;
    document.querySelector('#altera-nome').value = nome;    
    document.querySelector('#altera-descricao').value = descricao ? descricao : '';    
    document.querySelector('#altera-preco').value = preco.toFixed(2);    
    document.querySelector('#id_subgrupo').value = id_subgrupo; 

    let iconBtnAlteraFoto = document.querySelector('#icon-btn-altera-foto');//icone do botão altera foto
    let txtBtnAlteraFoto = document.querySelector('#txt-btn-altera-foto');//texto do botão altera foto
    let pathFoto = document.querySelectorAll('.path-foto');//<img> que receberá path da foto
    let acaoFoto = document.querySelector('#acao-foto');//texto da ação da foto (Alterar ou Inserir)

    //verifica se o item está em destaque
    if(destaque){
        document.querySelector('#altera-destaque').checked = true; 

    } else { 
        document.querySelector('#altera-destaque').checked = false;
    }

    //verifica se o item está ativo ou inativo
    ativo ? document.querySelector('#altera-ativo').checked = true : 
        document.querySelector('#altera-ativo').checked = false;
    !ativo ? document.querySelector('#altera-inativo').checked = true : 
        document.querySelector('#altera-inativo').checked;
    
    //exibe a alteração de destaque 
    foto && ativo && document.querySelector('#tags-altera-destaque').classList.remove('d-none');
    //Não exibe a alteração de destaque 
    !(foto && ativo) && document.querySelector('#tags-altera-destaque').classList.add('d-none');

    if(foto){//item possui foto
        
        if(!destaque){
            //exibe o botão de exclusão da foto
            document.querySelector('#btn-excluir-foto').classList.remove('d-none');
            
        } else{
            //não exibe o botão de exclusão da foto
            document.querySelector('#btn-excluir-foto').classList.add('d-none');
        }
        
                

        for(let f of pathFoto){
                f.src = `../img/${foto}`;//isere o caminho da foto
                f.classList.remove('d-none');//exibe a foto
        }
        
        iconBtnAlteraFoto.classList = 'fa fa-edit'; //altera o icone do botão de alterar foto
        txtBtnAlteraFoto.innerHTML = 'Alterar foto'; //altera o texto do botão de alterar foto

        acaoFoto.innerHTML = 'Alterar';//inserindo o texto da acao da foto no modal eltera foto

        //Altera o value do path da foto no modal alterar foto
        document.querySelector('#path-foto-alterar').value = `img/${foto}`;

        //Alterando o valor do path da foto no modal de exclusão do item
        document.querySelector('#path-foto-item-deletar').value = `img/${foto}`; 

    } else {//item não possui foto        
        for(let f of pathFoto){
            f.classList.add('d-none');//não exibe a foto
        }

        //não exibe o botão de exclusão da foto      
        document.querySelector('#btn-excluir-foto').classList.add('d-none');
        //não exibe o texto de atenção
        document.querySelector('#txt-atencao').classList.add('d-none');

        iconBtnAlteraFoto.classList= 'fa fa-plus'; //altera o icone do botão de alterar foto
        txtBtnAlteraFoto.innerHTML = 'Inserir foto'; //altera o texto do botão de alterar foto

        acaoFoto.innerHTML = 'Inserir';//inserindo o texto da acao da foto no modal eltera foto

        //Altera o value do path da foto
        document.querySelector('#path-foto-alterar').value = '';

        //Alterando o valor do path da foto no modal de exclusão do item
        document.querySelector('#path-foto-item-deletar').value = '';
    }
    
    

    //Alterando os valores dos itens do modal de exclusão da foto
    document.querySelector('#path-foto-deletar').value = `img/${foto}`;
    document.querySelector('#id-item').value = id;
    document.querySelector('#id-subgrupo').value = id_subgrupo; 

    //Alterando os valores dos itens do modal de editar/inserir foto    
    document.querySelector('#id-item-alterar').value = id;
    document.querySelector('#id-subgrupo-alterar').value = id_subgrupo;

    //Alterando os valores dos itens do modal de exclusão do item
    //document.querySelector('#path-foto-item-deletar').value = `img/${foto}`; 
    document.querySelector('#id-item-deleta').value = id; 
    document.querySelector('#id-subgrupo-deleta').value = id_subgrupo; 

    //seleciona os elementos que receberão o nome do item
    let nomeItem = document.querySelectorAll('.nome-item');
    
    for(let item of nomeItem){//insere o nome do item nos modais 
        item.innerHTML = nome;
    }

    $('#modal-altera-item').modal('show');//exibe o modal
}

function abrirModalAddBloqueio(data, url){
    changeDateBloq(data, url, 'horario-inicio', 'horario-final')
    document.querySelector('#bloq-data').value = data;
    $('#modal-add-bloqueio').modal('show');//exibe o modal
}

function showFormSetEstabelecimento(){//exibe/oculta o form de alteração do nome do estabelecimento
    let form = document.querySelector('#formSetNomeEstabelecimento');
    let btn = document.querySelector('#btnOpenFormSetNome');
    if(form.classList.contains('d-none')){
        form.classList.remove('d-none');
        btn.innerHTML = 'Cancelar alteração';

    } else { 
        form.classList.add('d-none');
        btn.innerHTML = 'Alterar nome do estabelecimento';
        let nomeAtual = document.querySelector('#nome-atual').value;
        document.querySelector('#novo-nome').value = nomeAtual;
    }
}

function getHorarios(url, date, id = 'alterar-horario'){//Insere os options em selects de datas
    let param = `date=${date}`;
    request = new XMLHttpRequest();
    request.open('post', url);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = () => { 
        if(request.readyState === 4 && request.status === 200){
            document.querySelector('#'+id).innerHTML = request.responseText;
        }
    }
    request.send(param);
    return true;
}

//Altera o value dos selects no modal add bloqueio no evento onchange da data
function changeDateBloq(value, url, id1, id2){
    getHorarios(url, value, id1);
    setTimeout(()=>{
        getHorarios(url, value, id2);
    }, '100');    
}

//Altera o value do select no modal add bloqueio no evento onchange de horário inicio
function changeHorarioInicio(){
    let hora1 = document.querySelector('#horario-inicio');
    let hora2 = document.querySelector('#horario-final');
    if(hora1.value > hora2.value){
        hora2.value = hora1.value;
    }
}

//Altera o value do selects no modal add bloqueio no evento onchange de horário fim
function changeHorarioFim(){
    let hora1 = document.querySelector('#horario-inicio');
    let hora2 = document.querySelector('#horario-final');
    if(hora2.value < hora1.value){        
        hora1.value = hora2.value;
    }
}

function getItensSelect(selected = 'todos'){//Altera os options do select de filtro de avaliação dos itens
    let subgrupo = document.querySelector('#avalProd-subgrupo');
    let url = subgrupo.dataset.url;
    let param = `id=${subgrupo.value}&selected=${selected}`;
    request = new XMLHttpRequest();
    request.open('post', url);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = () => { 
        if(request.readyState === 4 && request.status === 200){
            document.querySelector('#avalProd-produto').innerHTML = request.responseText;
        }
    }
    request.send(param);
}