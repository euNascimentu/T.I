function verificar() {
    let idade = window.document.getElementById('idadePessoa')
    let resposta = window.document.getElementById('resposta')
    let valor = Number(idade.value)


    if(valor <= 16){
        resposta.innerText = 'Você ainda não pode votar!'
    }
    else if(valor > 16 && valor < 17) {
        resposta.innerText = 'O seu voto é facultativo! (não obrigatório)'
    }
    else if(valor >= 18 &&  valor <= 65){
        resposta.innerText = 'O seu voto é obrigatório, dirija-se a uma urna!'}
    else {
        resposta.innerText = 'O seu voto é facultativo! (não obrigatório)'
    }
}