 //Verifica se o usuário está logado (checa o localStorage).
 // Se não estiver logado, redireciona para a página de login.
 
function verificarLogin() {
    const usuarioLogadoJson = localStorage.getItem('usuarioLogado');
    const loginPageUrl = '../login/login.php'; // Ajuste o caminho se necessário!

    // Se a chave 'usuarioLogado' não existir no localStorage, o usuário não está logado.
    if (!usuarioLogadoJson) {
        // Redireciona e encerra a execução do script.
        window.location.href = loginPageUrl;
        return false;
    }

    // Tenta fazer o parse do JSON para garantir que é um objeto válido.
    try {
        const usuarioLogado = JSON.parse(usuarioLogadoJson);

        // Uma checagem para garantir que o objeto não é null e contém dados essenciais.
        if (!usuarioLogado || !usuarioLogado.nome || !usuarioLogado.email) {
            // Se os dados estiverem incompletos, remove a chave e redireciona.
            localStorage.removeItem('usuarioLogado');
            window.location.href = loginPageUrl;
            return false;
        }

    } catch (e) {
        // Se houver um erro no parse (JSON inválido), redireciona.
        localStorage.removeItem('usuarioLogado');
        window.location.href = loginPageUrl;
        return false;
    }

    // Se chegar ate aqui o script continua normal
    return true;
}

// Chama a função 
verificarLogin();







//ESTILIZACAO DO PERFIL DO USUARIO (Revisado para buscar do localStorage)
document.addEventListener('DOMContentLoaded', (event) => {
    // 1. Elementos do DOM
    const iconeUsuario = document.getElementById('icone-usuario');
    const infoPainel = document.getElementById('info-usuario');
    const displayNome = document.getElementById('display-nome');
    const displayEmail = document.getElementById('display-email');

    // Verifica se os elementos cruciais existem (só para a página cadastro.html)
    if (!iconeUsuario || !infoPainel || !displayNome || !displayEmail) {
        // console.warn("Elementos do painel de usuário não encontrados ou a página atual não é a de cadastro.");
        return;
    }

    // 2. Função para carregar os dados e alternar a exibição
    function atualizarEExibirInfo() {
        // Tenta buscar o usuário logado no localStorage
        const usuarioJson = localStorage.getItem('usuarioLogado');
        let usuarioLogado = null;

        if (usuarioJson) {
            try {
                // Converte a string JSON de volta para um objeto
                usuarioLogado = JSON.parse(usuarioJson);
            } catch (e) {
                console.error("Erro ao fazer parse do usuário no localStorage", e);
            }
        }

        // Define os valores para exibição
         let nome; 
        let email; 

       
        if (usuarioLogado) {
            // Se conseguimos carregar o usuário: Usamos os dados reais.
            nome = usuarioLogado.nome;
            email = usuarioLogado.email;
        } else {
            // Se NÃO conseguimos (falha no parse ou não logado)
            nome = "Usuário Desconhecido (Faça Login)";
            email = "N/A";
        }
        // Preenche a div de informações
        displayNome.textContent = nome;
        displayEmail.textContent = email;

        // Alterna a visibilidade do painel 
        const isVisible = infoPainel.style.display === 'block';

        if (isVisible) {
            infoPainel.style.display = 'none'; // esconde
        } else {
            infoPainel.style.display = 'block'; // mostra
        }
    }

    //  Adiciona o evento de clique ao ícone
    iconeUsuario.addEventListener('click', atualizarEExibirInfo);
});