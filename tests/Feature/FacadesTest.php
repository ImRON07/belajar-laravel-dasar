<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FacadesTest extends TestCase
{
    public function testConfig()
    {
        $firstName1 = config('contoh.author.first');
        $firstName2 = Config::get('contoh.author.first');

        self::assertSame($firstName1, $firstName2);

        var_dump(Config::all());
    }
    public function testConfigDependency()
    {
        $config =$this->app->make('config');
        $firstName3 = $config->get('contoh.author.first');

        $firstName1 = config('contoh.author.first');
        $firstName2 = Config::get('contoh.author.first');

        self::assertSame($firstName1, $firstName2);
        self::assertSame($firstName1, $firstName3);

        var_dump(Config::all());
    }

    public function testFacadeMock()
    {
        Config::shouldReceive('get')
        // Log::shouldReceive();
        // App::shouldReceive();
        // Crypt::shouldReceive();
            ->with('contoh.author.first')
            ->andReturn('Imron Zha' );

        $firstName = config::get('contoh.author.first');

        self::assertSame('Imron Zha', $firstName);

        }

}
