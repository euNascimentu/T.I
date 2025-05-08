function mudarTexto() {

    let temp = window.document.getElementsByName('temp')
    let label1 = window.document.getElementById('lblTemp')

    if (temp[0].checked){
       //label1.oninput = "Celsius";
    } else {
        label1.innerHTML = "Fahrenheit";
    }
}