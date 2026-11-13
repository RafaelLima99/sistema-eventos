// Formulário de mensagem de WhatsApp:
// - destaca o template escolhido;
// - filtra a lista de templates pelo texto digitado na busca.
document.addEventListener('DOMContentLoaded', function () {
    var opcoes = document.querySelectorAll('.tpl-opt');
    var busca = document.getElementById('buscaTemplate');

    function destacarEscolhido() {
        var escolhido = document.querySelector('input[name="nome_template_meta"]:checked');

        opcoes.forEach(function (opcao) {
            // Destaca só a opção cujo radio está marcado.
            opcao.classList.toggle('on', opcao.contains(escolhido));
        });
    }

    document.querySelectorAll('input[name="nome_template_meta"]').forEach(function (radio) {
        radio.addEventListener('change', destacarEscolhido);
    });

    busca.addEventListener('input', function () {
        var texto = busca.value.toLowerCase();

        opcoes.forEach(function (opcao) {
            opcao.style.display = opcao.textContent.toLowerCase().includes(texto) ? '' : 'none';
        });
    });
});
