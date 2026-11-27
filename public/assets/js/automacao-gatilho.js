// Formulário de automação: esconde o campo Público quando o gatilho é "Após o pagamento".
// Nesse caso o público é sempre quem pagou (o backend fixa "Pagantes" ao salvar).
document.addEventListener('DOMContentLoaded', function () {
    var selectGatilho = document.querySelector('select[name="tipo_gatilho"]');
    var cardPublico = document.querySelector('.card-publico');

    function mostrarPublicoSeForAposInscricao() {
        cardPublico.hidden = selectGatilho.value === 'apos_pagamento';
    }

    selectGatilho.addEventListener('change', mostrarPublicoSeForAposInscricao);

    // Ao abrir a página: na edição, aplica o gatilho já salvo.
    mostrarPublicoSeForAposInscricao();
});
