document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const user = document.getElementById('username').value.toLowerCase();
    
    // Lógica simples de roteamento baseada no nome do usuário para o protótipo
    if (user.includes('supervisor')) {
        window.location.href = 'supervisor.html';
    } else {
        window.location.href = 'atuador.html';
    }
});