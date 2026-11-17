// Formulário de automação: mostra só o select de mensagem do canal escolhido (WhatsApp ou e-mail).
// Sem canal escolhido (criação), os dois selects ficam escondidos.
document.addEventListener('DOMContentLoaded', function () {
    var selectWhatsapp = document.querySelector('.mensagem-whatsapp');
    var selectEmail = document.querySelector('.mensagem-email');

    function mostrarSelectDoCanal() {
        var canalMarcado = document.querySelector('input[name="canal"]:checked');
        var canal = canalMarcado ? canalMarcado.value : null;

        selectWhatsapp.hidden = canal !== 'whatsapp';
        selectEmail.hidden = canal !== 'email';
    }

    document.querySelectorAll('input[name="canal"]').forEach(function (radio) {
        radio.addEventListener('change', mostrarSelectDoCanal);
    });

    // Ao abrir a página: na edição, mostra o select do canal já salvo.
    mostrarSelectDoCanal();
});
