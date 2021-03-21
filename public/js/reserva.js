function getHorarios(url, date){
    let param = `date=${date}`;
    request = new XMLHttpRequest();
    request.open('post', url);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.onreadystatechange = () => { 
        if(request.readyState === 4 && request.status === 200){
            document.querySelector('#reservaData').innerHTML = request.responseText;
        }
    }
    request.send(param);
}