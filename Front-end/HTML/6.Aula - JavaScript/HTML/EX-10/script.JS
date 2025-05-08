function verificar() {
    let vel = window.document.getElementById('velocidade')
    let resposta = window.document.getElementById('resposta')
    let verificar = window.document.getElementById('botao')
    let velocidade = Number(vel.value)

    if(velocidade <= 50){
        resposta.innerText = 'Você está dentro do limite da via... Bom passeio!'
    }
    else if(velocidade > 50 && velocidade < 110) {
        resposta.innerText = 'Você está acima da velocidade da via. Você receberá uma multa de velocidade!'
    }
    else if(velocidade > 110){
        resposta.innerText = 'Você está MUITO acima da velocidade da via... Você será multado e terá seu veiculo apreendido!'}
}
