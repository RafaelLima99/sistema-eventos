// Mensagens: ao clicar numa variável (.var-token), insere no campo de assunto ou de conteúdo
// onde a pessoa estava digitando por último, na posição do cursor.
document.addEventListener('DOMContentLoaded', function () {
    var campos = document.querySelectorAll('input[name="assunto"], textarea[name="conteudo"]');
    var campoAtivo = document.querySelector('textarea[name="conteudo"]');

    campos.forEach(function (campo) {
        campo.addEventListener('focus', function () {
            campoAtivo = campo;
        });
    });

    document.querySelectorAll('.var-token').forEach(function (token) {
        token.style.cursor = 'pointer';

        token.addEventListener('click', function () {
            if (!campoAtivo) return;

            var inicio = campoAtivo.selectionStart;
            var fim = campoAtivo.selectionEnd;

            campoAtivo.value = campoAtivo.value.slice(0, inicio) + token.textContent + campoAtivo.value.slice(fim);
            campoAtivo.focus();
            campoAtivo.setSelectionRange(inicio + token.textContent.length, inicio + token.textContent.length);
        });
    });
});
