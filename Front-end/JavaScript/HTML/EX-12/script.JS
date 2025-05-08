function verificar() {
    let diaDaSemana = window.document.getElementById('diaSemana')
    let resposta = window.document.getElementById('resposta')
    let valor = Number(diaDaSemana.value)

    switch (valor) {

        case 1:
            resposta.innerText = ('Você escolheu "Segunda-Feira"')

            break

        case 2:

            resposta.innerText = ('Você escolheu "Terça-Feira"')

            break

        case 3:

            resposta.innerText = ('Você escolheu "Quarta-Feira"')

            break

        case 4: 

            resposta.innerText = ('Você escolheu "Quinta-Feira"')

            break

        case 5:

            resposta.innerText = ('Você escolheu "Sexta-Feira"')

            break

        case 6:

            resposta.innerText = ('Você escolheu "Sabado"')

            break

        case 7:

            resposta.innerText = ('Você escolheu "Domingo"')

            break

        default: 
        
            resposta.innerText = ("Escolha um numero....")

    }

}