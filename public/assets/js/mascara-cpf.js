// Máscara de CPF: em todo campo com o atributo data-mascara-cpf,
// "31204827111" vira "312.048.271-11" enquanto a pessoa digita.
document.addEventListener('DOMContentLoaded', function () {
    function aplicarMascara(campo) {
        var digitos = campo.value.replace(/\D/g, '').slice(0, 11);

        campo.value = digitos
            .replace(/^(\d{3})(\d)/, '$1.$2')
            .replace(/^(\d{3})\.(\d{3})(\d)/, '$1.$2.$3')
            .replace(/\.(\d{3})(\d)/, '.$1-$2');
    }

    document.querySelectorAll('[data-mascara-cpf]').forEach(function (campo) {
        campo.addEventListener('input', function () {
            aplicarMascara(campo);
        });

        // Formata também o valor que já veio preenchido (ex.: CPF da busca anterior).
        aplicarMascara(campo);
    });
});
