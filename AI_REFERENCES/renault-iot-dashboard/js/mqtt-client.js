// Configuração do Broker MQTT Público para testes (HiveMQ WebSockets)
const mqttHost = "broker.hivemq.com";
const mqttPort = 8000;
const clientId = "renault_client_" + parseInt(Math.random() * 100, 10);

// Instanciando o cliente Paho MQTT
const client = new Paho.MQTT.Client(mqttHost, mqttPort, clientId);

// Tópicos do sistema
const TOPIC_ESTOQUE = "fabrica/linha1/pecas";
const TOPIC_TEMPO = "fabrica/linha1/tempo";
const TOPIC_CONTROLE = "fabrica/linha1/controle"; // Para o botão de "Iniciar Turno"

// Callbacks
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessageArrived;

// Conectar ao Broker
client.connect({
    onSuccess: onConnect,
    useSSL: false // Setar true se for rodar em HTTPS com broker seguro no futuro
});

function onConnect() {
    console.log("Conectado ao Broker MQTT!");
    const statusBadge = document.getElementById("mqtt-status");
    if(statusBadge) {
        statusBadge.classList.replace("bg-red-500", "bg-green-500");
        statusBadge.innerText = "MQTT Online";
    }
    
    // Inscrever-se nos tópicos
    client.subscribe(TOPIC_ESTOQUE);
    client.subscribe(TOPIC_TEMPO);
    client.subscribe(TOPIC_CONTROLE);
}

function onConnectionLost(responseObject) {
    if (responseObject.errorCode !== 0) {
        console.log("Conexão perdida: " + responseObject.errorMessage);
        const statusBadge = document.getElementById("mqtt-status");
        if(statusBadge) {
            statusBadge.classList.replace("bg-green-500", "bg-red-500");
            statusBadge.innerText = "Reconectando...";
        }
    }
}

// Essa função distribui a mensagem para as telas, será sobrescrita/usada no supervisor.js
function onMessageArrived(message) {
    console.log("Mensagem Recebida: " + message.destinationName + " -> " + message.payloadString);
    
    // Dispara um evento customizado no navegador para que a tela do supervisor pegue os dados
    const event = new CustomEvent('mqttMessage', { 
        detail: { topic: message.destinationName, payload: message.payloadString } 
    });
    document.dispatchEvent(event);
}

// Função utilitária para Publicar (Usada pelo botão do Atuador)
function publishMessage(topic, payload) {
    const message = new Paho.MQTT.Message(payload);
    message.destinationName = topic;
    client.send(message);
}