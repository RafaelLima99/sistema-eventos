// Formulário de disparo: destaca as opções marcadas (contatos e eventos),
// mostra a lista de eventos só com "Inscritos em evento" e filtra essa lista pela busca.
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('fDisparo');
    if (!form) return;

    var listaEventos = document.getElementById('contatosEventos');
    var busca = document.getElementById('buscaEvento');

    function atualizarTela() {
        // Destaca (classe "on") os cards e eventos que estão marcados.
        form.querySelectorAll('.opt-card, .ev-opt').forEach(function (opcao) {
            var input = opcao.querySelector('input');
            if (input) opcao.classList.toggle('on', input.checked);
        });

        // A lista de eventos só aparece com "Inscritos em evento" (value 0).
        var contatos = form.querySelector('input[name="todos_contatos"]:checked');
        var porEvento = !!contatos && contatos.value === '0';
        listaEventos.classList.toggle('d-none', !porEvento);

        // Checkbox desabilitado não é enviado: com "Todos os contatos", nenhum evento vai para o servidor.
        listaEventos.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.disabled = !porEvento;
        });
    }

    // Qualquer clique em radio ou checkbox do formulário atualiza a tela.
    form.addEventListener('change', atualizarTela);

    // Esconde os eventos cujo nome não tem o texto digitado.
    busca.addEventListener('input', function () {
        var termo = busca.value.trim().toLowerCase();
        listaEventos.querySelectorAll('.ev-opt').forEach(function (evento) {
            evento.classList.toggle('d-none', evento.textContent.toLowerCase().indexOf(termo) === -1);
        });
    });

    // Ao abrir a página (na edição ou depois de um erro de validação), já mostra o que vem marcado.
    atualizarTela();
});
