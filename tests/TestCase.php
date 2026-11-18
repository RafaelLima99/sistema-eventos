<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Nenhum teste chama APIs de verdade (ex.: Meta): requisição sem Http::fake() faz o teste falhar.
        Http::preventStrayRequests();
    }
}
