// Aguarda o clique no botão de Iniciar Turno
document.getElementById('btn-start').addEventListener('click', function() {
    // Publica a mensagem no tópico de controle
    publishMessage(TOPIC_CONTROLE, "TURNO_INICIADO");

    // Feedback visual para o operador
    this.classList.replace('bg-green-600', 'bg-gray-500');
    this.innerText = "TURNO EM ANDAMENTO...";
    this.disabled = true; // Desabilita o botão para não clicar duas vezes

    alert("Turno iniciado! O sistema está aguardando as leituras dos sensores ultrassônicos e da balança na bancada.");
});