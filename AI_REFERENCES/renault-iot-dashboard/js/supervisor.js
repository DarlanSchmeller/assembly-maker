// 1. Configuração Inicial do Gráfico (Chart.js)
const ctx = document.getElementById('cycleChart').getContext('2d');
const cycleChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [], // Nome das caixas no eixo X (Ex: Cx 1, Cx 2)
        datasets: [{
            label: 'Tempo de Ciclo (segundos)',
            data: [], // Os tempos entrarão aqui
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

// Variáveis de controle para o supervisor
let pecasTotais = 0;
let contadorCaixas = 0;

// 2. Escutando os dados que chegam do MQTT (enviados pelo mqtt-client.js)
document.addEventListener('mqttMessage', function(e) {
    const topic = e.detail.topic;
    const payload = e.detail.payload;

    // A. Atualizar o log (tela preta de eventos)
    const logDiv = document.getElementById('event-log');
    const timeString = new Date().toLocaleTimeString();
    logDiv.innerHTML += `<p class="mb-1">[${timeString}] <span class="text-yellow-400">${topic}</span>: ${payload}</p>`;
    logDiv.scrollTop = logDiv.scrollHeight; // Rola a barrinha sempre para o final

    // B. Lógica de Atualização dos Números (KPIs)
    if (topic === TOPIC_CONTROLE && payload === "TURNO_INICIADO") {
        document.getElementById('kpi-estoque').innerText = "Aguardando Caixa...";
        document.getElementById('kpi-estoque').classList.add('text-yellow-600');
    } 
    
    else if (topic === TOPIC_ESTOQUE) {
        // Se a mensagem for um número (ex: "3", "2", "1")
        if (!isNaN(payload)) {
            document.getElementById('kpi-estoque').innerText = payload + " peças";
            document.getElementById('kpi-estoque').classList.remove('text-yellow-600');
            
            // Soma na produção total
            pecasTotais += parseInt(payload);
            document.getElementById('kpi-pecas').innerText = pecasTotais;
        } else {
            // Se for texto (ex: "CAIXA_REMOVIDA")
            document.getElementById('kpi-estoque').innerText = payload;
        }
    } 
    
    else if (topic === TOPIC_TEMPO) {
        // Atualiza o card de tempo médio
        document.getElementById('kpi-tempo').innerText = payload + "s";

        // Adiciona um novo ponto no Gráfico
        contadorCaixas++;
        cycleChart.data.labels.push("Cx " + contadorCaixas);
        cycleChart.data.datasets[0].data.push(parseInt(payload));
        cycleChart.update(); // Manda o gráfico se redesenhar
    }
});