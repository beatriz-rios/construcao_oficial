// Base de Dados Simples (Lista de Usuários Permitidos)
const USUARIOS_PERMITIDOS = [
    { email: 'vitor@empresa.com', senha: '123456', nome: 'Vitor' }, 
    { email: 'bia@empresa.com', senha: '123456', nome: 'Bia' }, 
    { email: 'gideao@empresa.com', senha: '123456', nome: 'Gideao' } 
];

//  URLs e Tempos 
const URL_LOGIN = 'login.php'; 
const URL_SUCESSO = 'login.php';
const TEMPO_REDIRECIONAMENTO_MS = 4000;

// --- 1.3 Elementos HTML (Puxando todos os IDs) 
// Elementos da tela de Login
const FORMULARIO = document.getElementById('formulario-login');
const MENSAGEM_ERRO = document.getElementById('mensagem-erro');
const EMAIL_INPUT = document.getElementById('email-input');
const SENHA_INPUT = document.getElementById('senha-input');
const NOME_INPUT = document.getElementById('nome-input');

// Elementos do painel de informações do usuário
const ICONE_USUARIO = document.getElementById('icone-usuario');
const INFO_PAINEL = document.getElementById('info-usuario');
const DISPLAY_NOME = document.getElementById('display-nome');
const DISPLAY_EMAIL = document.getElementById('display-email');
const BTN_LOGOUT = document.getElementById('btn-logout');


// FUNÇÃO DE FALHA 


// Função que lida com a falha de autenticação (exibe mensagem e redireciona após tempo)
function lidarComFalha(motivoDaFalha) {
    
    const mensagemCompleta = `FALHA DE AUTENTICAÇÃO: ${motivoDaFalha} Redirecionando para a tela de login em ${TEMPO_REDIRECIONAMENTO_MS / 1000} segundos...`;

    
    alert(mensagemCompleta); 
    
    if(MENSAGEM_ERRO) {
       
        MENSAGEM_ERRO.textContent = motivoDaFalha;
        
        MENSAGEM_ERRO.style.display = 'block'; 
    }

    //  Redirecionar novamente à tela de login após um pequeno atraso
    setTimeout(() => {
        window.location.replace(URL_LOGIN); 
    }, TEMPO_REDIRECIONAMENTO_MS);
}


// Vereficação LOGIN (Principal)


// Apenas executa o tratamento de login se o formulário for encontrado.
if (FORMULARIO) {
    FORMULARIO.addEventListener('submit', function(evento) { // Usando function()
        evento.preventDefault(); 
        
        
        if (!EMAIL_INPUT || !SENHA_INPUT) {
            console.error("Campos de email/senha não encontrados no DOM. Verifique os IDs 'email-input' e 'senha-input'.");
            return;
        }
        
        // Obtém os valores digitados.
        const emailDigitado = EMAIL_INPUT.value;
        const senhaDigitada = SENHA_INPUT.value;

        // Procura na lista de usuários permitidos.
        const usuarioEncontrado = USUARIOS_PERMITIDOS.find(usuario => 
            usuario.email === emailDigitado && usuario.senha === senhaDigitada
        );

        if (usuarioEncontrado) {
            // Caso de Sucesso:
            
            localStorage.setItem('usuarioLogado', JSON.stringify(usuarioEncontrado)); 
            
            alert(`Bem-vindo(a), ${usuarioEncontrado.nome}! Redirecionando...`);
            
            // Redireciona para a URL de sucesso.
            window.location.replace(URL_SUCESSO); 
            
        } else {
            //Caso de Falha:
            const motivo = "Credenciais inválidas. Verifique seu e-mail e senha."; 
            lidarComFalha(motivo);
        }

        //  RESETA OS CAMPOS APÓS TENTATIVA
        EMAIL_INPUT.value = '';
        SENHA_INPUT.value = '';
        if (NOME_INPUT) {
            NOME_INPUT.value = '';
        }
    });
}


//  EXIBIÇÃO E ESTILIZAÇÃO DO PERFIL


document.addEventListener('DOMContentLoaded', function(event) { 
    
    // Verifica se os elementos cruciais do perfil existem
    if (!ICONE_USUARIO || !INFO_PAINEL || !DISPLAY_NOME || !DISPLAY_EMAIL) {
        return;
    }

    // Função para carregar os dados do localStorage e alternar a exibição do painel.
    function atualizarEExibirInfo() {
        // Tenta buscar o usuário logado no localStorage
        const usuarioJson = localStorage.getItem('usuarioLogado');
        let usuarioLogado = null;

        if (usuarioJson) {
            try {
                // Tenta converter o texto JSON em objeto
                usuarioLogado = JSON.parse(usuarioJson);
            } catch (e) {
                // Se a conversão falhar, apenas avisamos e mantemos usuarioLogado como null.
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
        DISPLAY_NOME.textContent = nome;
        DISPLAY_EMAIL.textContent = email;

        // Alterna a visibilidade do painel 
        const isVisible = INFO_PAINEL.style.display === 'block';
        
        if (isVisible) {
            INFO_PAINEL.style.display = 'none'; // Esconde
        } else {
            INFO_PAINEL.style.display = 'block'; // Mostra
        }
    }

    // Adiciona o evento de clique ao ícone do usuário.
    ICONE_USUARIO.addEventListener('click', atualizarEExibirInfo);
    
    // LÓGICA DE LOGOUT 
    if (BTN_LOGOUT) {
        BTN_LOGOUT.addEventListener('click', function() { 
            // Remove os dados do usuário do armazenamento local
            localStorage.removeItem('usuarioLogado'); 
            alert('Você foi desconectado.');
            // Redireciona para a página de login
            window.location.replace(URL_LOGIN); 
        });
    }
});