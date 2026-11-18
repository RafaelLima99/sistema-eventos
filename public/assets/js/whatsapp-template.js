// Formulário de mensagem de WhatsApp:
// - destaca o template escolhido;
// - filtra a lista de templates pelo texto digitado na busca.
// Os templates aprovados já vêm todos na página; a busca só esconde os que não combinam (não chama o servidor).
document.addEventListener('DOMContentLoaded', function () {
    var opcoesDeTemplate = document.querySelectorAll('.tpl-opt');
    var busca = document.getElementById('buscaTemplate');

    function destacarEscolhido() {
        var escolhido = document.querySelector('input[name="nome_template_meta"]:checked');

        opcoesDeTemplate.forEach(function (opcao) {
            // Destaca (classe "on") só o card cujo radio está marcado.
            opcao.classList.toggle('on', opcao.contains(escolhido));
        });
    }

    // Ao escolher outro template, troca o destaque.
    document.querySelectorAll('input[name="nome_template_meta"]').forEach(function (radio) {
        radio.addEventListener('change', destacarEscolhido);
    });

    // "input" dispara a cada letra digitada ou apagada.
    busca.addEventListener('input', function () {
        // Minúsculas dos dois lados: "Lembrete" e "lembrete" dão o mesmo resultado.
        var texto = busca.value.trim().toLowerCase();

        opcoesDeTemplate.forEach(function (opcao) {
            // textContent é todo o texto do card: nome, categoria e texto do template.
            var combina = opcao.textContent.toLowerCase().includes(texto);

            // Esconde o card que não contém o texto digitado.
            opcao.classList.toggle('d-none', !combina);
        });
    });
});
