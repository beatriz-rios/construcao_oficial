/**
 * Verifica se o usuário está logado (checa o localStorage).
 * Se não estiver logado, redireciona para a página de login.
 */
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

        // Opcional: Uma checagem adicional para garantir que o objeto não é null e contém dados essenciais.
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

    // Se chegou até aqui, o usuário está logado e o script continua a execução normal.
    return true;
}

// Chama a função imediatamente para proteger a página
verificarLogin();

// ESTILIZACAO E LOGICA DO PERFIL DO USUARIO E TABELA DE PRODUTOS
document.addEventListener('DOMContentLoaded', (event) => {
    // --- Lógica do Painel de Perfil (Copiei e organizei a lógica que estava no arquivo original) ---
    const iconeUsuario = document.getElementById('icone-usuario');
    const infoPainel = document.getElementById('info-usuario');
    const displayNome = document.getElementById('display-nome');
    const displayEmail = document.getElementById('display-email');

    // Verifica se os elementos cruciais existem (para evitar erros em páginas sem esses elementos)
    if (iconeUsuario && infoPainel && displayNome && displayEmail) {
        // Função para carregar os dados e alternar a exibição
        function atualizarEExibirInfo() {
            const usuarioJson = localStorage.getItem('usuarioLogado');
            let usuarioLogado = null;

            if (usuarioJson) {
                try {
                    usuarioLogado = JSON.parse(usuarioJson);
                } catch (e) {
                    console.error("Erro ao fazer parse do usuário no localStorage", e);
                }
            }

            // Preenche a div de informações
            let nome; // Primeiro, declare a variável
        if (usuarioLogado) {
            // Se a condição for VERDADEIRA
            nome = usuarioLogado.nome;
        } else {
            // Se a condição for FALSA
            nome = "Usuário Desconhecido (Faça Login)";
        }
        // Agora, a variável 'nome' tem o valor correto.


        let email; // Primeiro, declare a variável
        if (usuarioLogado) {
            // Se a condição for VERDADEIRA
            email = usuarioLogado.email;
        } else {
            // Se a condição for FALSA
            email = "Usuário Desconhecido (Faça Login)";
        }
            // Alterna a visibilidade do painel
            const isVisible = infoPainel.style.display === 'block';
            infoPainel.style.display = isVisible ? 'none' : 'block';
        }

        // Adiciona o evento de clique ao ícone
        iconeUsuario.addEventListener('click', atualizarEExibirInfo);
    }
    
    // --- Lógica da Tabela de Produtos (Exemplo de onde sua lógica principal deve ir) ---
    // Seu código de carregamento, filtragem e exibição da tabela de estoque deve vir aqui.
    console.log("Página de Estoque Carregada. O usuário está logado e pode ver os produtos.");
    // Exemplo: carregarProdutos();
});