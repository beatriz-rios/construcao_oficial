
//  FUNÇÃO DE VERIFICAÇÃO DE LOGIN

 // Verifica se o usuário tem os dados de login no localStorage.
 // Se o token estiver faltando ou for inválido, redireciona o usuário para o login.
 
function verificarLogin() {
    const usuarioLogadoJson = localStorage.getItem('usuarioLogado');
    const loginPageUrl = '../login/login.php'; // Ajuste o caminho se necessário!

    // Se a chave 'usuarioLogado' não existir, redireciona.
    if (!usuarioLogadoJson) {
        window.location.href = loginPageUrl; 
        return false;
    }
    
    // Tenta fazer o parse e checa a validade dos dados.
    try {
        const usuarioLogado = JSON.parse(usuarioLogadoJson);
        // Checa se o objeto é nulo ou não tem dados essenciais
        if (!usuarioLogado || !usuarioLogado.nome || !usuarioLogado.email) {
            localStorage.removeItem('usuarioLogado'); // Limpa dados corrompidos
            window.location.href = loginPageUrl; 
            return false;
        }

    } catch (erro) {
        // JSON inválido
        console.error("Erro no parse do objeto 'usuarioLogado':", erro);
        localStorage.removeItem('usuarioLogado');
        window.location.href = loginPageUrl; 
        return false;
    }

    // Login OK
    return true;
}

// Chama a função imediatamente para proteger a página ANTES de carregar o resto
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
            } catch (erro) {
                console.error("Erro ao fazer parse do usuário no localStorage", e);
            }
        }
  let nome; 
        let email; 

       
        if (usuarioLogado) {
            // Se conseguimos carregar o usuário: Usamos os dados reais.
            nome = usuarioLogado.nome;
            email = usuarioLogado.email;
        } else {
            // Se NÃO conseguimos (falha no parse ou não logado): Usamos as mensagens padrão.
            nome = "Usuário Desconhecido (Faça Login)";
            email = "N/A";
        }
        // Preenche a div de informações
        displayNome.textContent = nome;
        displayEmail.textContent = email;
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