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
        const nome = usuarioLogado ? usuarioLogado.nome : "Usuário Desconhecido (Faça Login)";
        const email = usuarioLogado ? usuarioLogado.email : "N/A";

        // Preenche a div de informações
        displayNome.textContent = nome;
        displayEmail.textContent = email;

        // 3. Alterna a visibilidade do painel (como um "toggle")
        const isVisible = infoPainel.style.display === 'block';
        
        if (isVisible) {
            infoPainel.style.display = 'none'; // Esconde
        } else {
            infoPainel.style.display = 'block'; // Mostra
        }
    }

    // 4. Adiciona o evento de clique ao ícone
    iconeUsuario.addEventListener('click', atualizarEExibirInfo);
    });