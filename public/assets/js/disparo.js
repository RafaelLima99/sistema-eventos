// Formulário de disparo: card 1 (contatos) e card 2 (filtro) — seleção funcional, sem back-end.
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('fDisparo');
    if (!form) return;

    var box = document.getElementById('contatosEventos');
    var busca = document.getElementById('buscaEvento');

    function sincronizar() {
        // destaca a opção marcada (radio e checkbox dentro de .opt-card / .ev-opt)
        form.querySelectorAll('.opt-card, .ev-opt').forEach(function (el) {
            var i = el.querySelector('input');
            if (i) el.classList.toggle('on', i.checked);
        });

        // select de eventos só aparece (e só é enviado) com "Contatos inscritos em evento"
        var modo = form.querySelector('input[name="contatos"]:checked');
        var porEvento = !!modo && modo.value === 'evento';
        box.classList.toggle('d-none', !porEvento);
        box.querySelectorAll('input[type="checkbox"]').forEach(function (c) { c.disabled = !porEvento; });
    }

    form.addEventListener('change', sincronizar);

    busca.addEventListener('input', function () {
        var termo = busca.value.trim().toLowerCase();
        box.querySelectorAll('.ev-opt').forEach(function (op) {
            op.classList.toggle('d-none', op.textContent.toLowerCase().indexOf(termo) === -1);
        });
    });

    sincronizar();
});
