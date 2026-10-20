<?php

use App\Models\Contato;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;

// Antes de cada teste, cria um usuário e faz login com ele.
beforeEach(function () {
    criarUsuarioLogado();
});

/**
 * Envia um CSV com o conteúdo informado para a importação.
 */
function importarCsv(string $conteudo): TestResponse
{
    return test()->post(route('contatos.importacao.store'), [
        'arquivo' => UploadedFile::fake()->createWithContent('contatos.csv', $conteudo),
    ]);
}

test('importa contatos de um CSV', function () {
    importarCsv(
        "nome,email,whatsapp,cpf,cargo,negocio\n".
        "Ana Silva,ana@example.com,(11) 99012-3145,312.048.271-11,CEO,Agência\n".
        "Carlos Souza,carlos@example.com,,,,\n"
    )->assertRedirect(route('contatos.index'))
        ->assertSessionHas('sucesso', 'Importação concluída: 2 criado(s) e 0 atualizado(s).');

    $ana = Contato::where('email', 'ana@example.com')->firstOrFail();

    expect(Contato::count())->toBe(2)
        ->and($ana->cpf)->toBe('31204827111')
        ->and($ana->telefone)->toBe('(11) 99012-3145')
        ->and($ana->negocio)->toBe('Agência');
});

test('atualiza contatos com CPF ou e-mail já cadastrados', function () {
    $porCpf = Contato::factory()->create(['cpf' => '31204827111', 'cargo' => 'Analista']);
    $porEmail = Contato::factory()->create(['email' => 'carlos@example.com', 'cargo' => 'Analista']);

    importarCsv(
        "nome,email,cpf,cargo\n".
        "Ana Silva,,312.048.271-11,CEO\n".
        "Carlos Souza,CARLOS@example.com,,CMO\n"
    )->assertSessionHas('sucesso', 'Importação concluída: 0 criado(s) e 2 atualizado(s).');

    expect(Contato::count())->toBe(2)
        ->and($porCpf->refresh()->cargo)->toBe('CEO')
        ->and($porEmail->refresh()->cargo)->toBe('CMO');
});

test('não apaga dados existentes com colunas vazias', function () {
    $contato = Contato::factory()->create(['cpf' => '31204827111', 'telefone' => '(11) 99012-3145']);

    importarCsv("nome,cpf,whatsapp\nAna Silva,31204827111,\n");

    expect($contato->refresh()->telefone)->toBe('(11) 99012-3145');
});

test('aceita CSV do Excel com ponto e vírgula e acentos', function () {
    $conteudo = mb_convert_encoding("Nome;E-mail;Negócio\nJoão Araújo;joao@example.com;Educação\n", 'Windows-1252', 'UTF-8');

    importarCsv($conteudo);

    expect(Contato::firstOrFail())
        ->nome->toBe('João Araújo')
        ->email->toBe('joao@example.com')
        ->negocio->toBe('Educação');
});

test('ignora linhas inválidas e informa quais foram', function () {
    importarCsv(
        "nome,email,cpf\n".
        "Ana Silva,ana@example.com,\n".
        ",sem-nome@example.com,\n".
        "Carlos Souza,email-invalido,\n".
        "Bruno Alves,,\n"
    )->assertSessionHas('erro', '3 linha(s) ignorada(s) por dados inválidos: 3, 4, 5.');

    expect(Contato::count())->toBe(1);
});

test('recusa arquivo sem a coluna nome', function () {
    importarCsv("email,cpf\nana@example.com,\n")
        ->assertSessionHasErrorsIn('importacao', 'arquivo');

    expect(Contato::count())->toBe(0);
});
