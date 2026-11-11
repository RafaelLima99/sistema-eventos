<?php

namespace App\Services;

use App\Models\Inscrito;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Monta a consulta de inscritos de acordo com os filtros da busca.
 *
 * Usado pela listagem e pela exportação em CSV, para as duas mostrarem os mesmos inscritos.
 */
class InscritoBuscaService
{
    /**
     * Devolve os inscritos que combinam com os filtros da URL (busca_nome, evento, cpf, status_ingresso e status_presenca).
     * Filtros vazios ou ausentes são ignorados.
     *
     * @return Builder<Inscrito>
     */
    public function buscar(Request $request): Builder
    {
        // with() busca os contatos e eventos de todos os inscritos de uma vez, em vez de uma consulta por linha.
        $query = Inscrito::query()->with(['contato', 'evento']);

        if ($request->filled('busca_nome')) {
            // O nome está na tabela contatos: whereHas filtra os inscritos pelo contato, e a função diz como filtrar o contato.
            $query->whereHas('contato', function ($contato) use ($request) {
                $contato->whereLike('nome', '%'.$request->string('busca_nome').'%');
            });
        }

        if ($request->filled('evento')) {
            $query->where('evento_id', $request->integer('evento'));
        }

        if ($request->filled('cpf')) {
            // O CPF é salvo sem máscara: "312.048.271-11" vira "31204827111" para achar o contato.
            $cpf = preg_replace('/\D/', '', $request->string('cpf'));
            // O CPF está na tabela contatos: filtra os inscritos cujo contato tem esse CPF.
            $query->whereHas('contato', fn ($contato) => $contato->where('cpf', $cpf));
        }

        if ($request->filled('status_ingresso')) {
            $query->where('status_ingresso', $request->string('status_ingresso'));
        }

        if ($request->filled('status_presenca')) {
            $query->where('status_presenca', $request->string('status_presenca'));
        }

        return $query;
    }
}
