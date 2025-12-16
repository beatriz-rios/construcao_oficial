// Base de Dados Simples (Lista de Usuários Permitidos)
const usuariosPermitidos = [
    { email: 'vitor@empresa.com', senha: '123456', nome: 'Vitor' }, 
    { email: 'bia@empresa.com', senha: '123456', nome: 'Bia' }, 
    { email: 'gideao@empresa.com', senha: '123456', nome: 'Gideao' } 
];

//  URLs e Tempos 
const urlLogin = 'login.php'; 
const urlSucesso = 'login.php';
const tempoRedirecionamentoMs = 4000;

//  Elementos HTML (Puxando todos os IDs) 
// Elementos da tela de Login
//UPPER_SNAKE_CASE (todas maiúsculas com underscores) representam constantes globais ou valores que nunca devem mudar | serve como um sinal visual forte
const formulario = document.getElementById('formulario-login');
const mensagemErro = document.getElementById('mensagem-erro');
const emailInput = document.getElementById('email-input');
const senhaInput = document.getElementById('senha-input');
const nomeInput = document.getElementById('nome-input');

// Elementos do painel de informações do usuário
const iconeUsuario = document.getElementById('icone-usuario');
const infoPainel = document.getElementById('info-usuario');
const displayNome = document.getElementById('display-nome');
const displayEmail = document.getElementById('display-email');
const btnLogout = document.getElementById('btn-logout');


// FUNÇÃO DE FALHA 
// Função que lida com a falha de autenticação (exibe mensagem e redireciona após tempo)
function lidarComFalha(motivoDaFalha) {
    //mensagem completa que será exibida ao usuário, incluindo o tempo de espera.
    const mensagemCompleta = `FALHA DE AUTENTICAÇÃO: ${motivoDaFalha} Redirecionando para a tela de login em ${tempoRedirecionamentoMs / 1000} segundos...`;

    
    alert(mensagemCompleta); 
    
     // Verificar se o elemento de mensagem de erro existe no HTML.
    if(mensagemErro) {
       
        // Atualiza o texto do elemento HTML com o motivo específico da falha.
        mensagemErro.textContent = motivoDaFalha;
        
        
        // Torna a div da mensagem de erro visível (se estava oculta).
        mensagemErro.style.display = 'block'; 
    }

    //  Redirecionar novamente à tela de login após um pequeno atraso
    setTimeout(() => {
        window.location.replace(urlLogin); 
    }, tempoRedirecionamentoMs);
}






// Vereficação de LOGIN (Principal)
// Apenas executa o tratamento de login se o formulário for encontrado.
if (formulario) {
    // Adiciona um 'ouvinte' (listener) que espera o evento de 'submit' (envio) do formulário.
    formulario.addEventListener('submit', function(evento) { 
        // Impede o comportamento padrão do navegador (que seria recarregar a página).
        evento.preventDefault(); 
        
        
          // Verifica se os campos de input foram corretamente localizados no HTML.
        if (!emailInput || !senhaInput) {
            console.error("Campos de email/senha não encontrados no DOM. Verifique os IDs 'email-input' e 'senha-input'.");
            return;
        }
        






        // Obtém os valores digitados.
        const emailDigitado = emailInput.value;
        const senhaDigitada = senhaInput.value;

        // Procura na lista de usuários permitidos.
        const usuarioEncontrado = usuariosPermitidos.find(usuario => 
            usuario.email === emailDigitado && usuario.senha === senhaDigitada
        );

        if (usuarioEncontrado) {
            // Caso de Sucesso grava os dados do usuário no localStorage
             // JSON.stringify() converte o objeto JavaScript em uma string, pois o localStorage só armazena strings.
            localStorage.setItem('usuarioLogado', JSON.stringify(usuarioEncontrado)); 
            

            alert(`Bem-vindo(a), ${usuarioEncontrado.nome}! Redirecionando...`);
            
            // Redireciona para a URL de sucesso.
            window.location.replace(urlSucesso); 
            
        } else {
            //Caso de Falha:
            const motivo = "Credenciais inválidas. Verifique seu e-mail e senha."; 
            // Chama a função de lidar de falha.
            lidarComFalha(motivo);
        }

        //  RESETA OS CAMPOS APÓS TENTATIVA
        emailInput.value = '';
        senhaInput.value = '';
        if (nomeInput) {
            nomeInput.value = '';
        }
    });
}








//  EXIBIÇÃO E ESTILIZAÇÃO DO PERFIL
// Garante que o código só será executado depois que todo o HTML da página estiver carregado
document.addEventListener('DOMContentLoaded', function(event) { 
    
    // Verifica se os elementos cruciais do perfil existem
    if (!iconeUsuario || !infoPainel || !displayNome || !displayEmail) {
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

    // Adiciona o evento de clique ao ícone do usuário.
    iconeUsuario.addEventListener('click', atualizarEExibirInfo);
    









    // LÓGICA DE LOGOUT 
    if (btnLogout) {
        btnLogout.addEventListener('click', function() { 
            // Remove os dados do usuário do armazenamento local
            localStorage.removeItem('usuarioLogado'); 
            alert('Você foi desconectado.');
            // Redireciona para a página de login
            window.location.replace(urlLogin); 
        });
    }
});