<?php

namespace Tests\Feature;

use App\Data\Bar;
use App\Data\Foo;
use App\Data\Person;
use App\Services\HelloService;
use App\Services\HelloServiceIndonesia;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ServiceContainer extends TestCase
{
    public function testDependency()
    {
        //$foo = new Foo(); //Cara sebelumnya

        //Membuat class baru menggunakan Service cntainer
        $foo1 = $this->app->make(Foo::class); // membuat class foo baru
        $foo2 = $this->app->make(Foo::class); // membuat class foo baru

        self::assertEquals('Foo', $foo1->foo()); //Meng-cek apakah class-nya benar ada atau tidak
        self::assertEquals('Foo', $foo2->foo()); //Meng-cek apakah class-nya benar ada atau tidak
        self::assertNotSame($foo1, $foo2); // Memastikan object foo1 dan foo2 berbeda
    }

    public function testBind()
    {
        /*
        $person = $this->app->make(Person::class); //new person
        self::assertNotNull($person);
        */

        $this->app->bind(Person::class, function ($app){
            return new Person("Imron", "Lukita");
        });

        $person1 = $this->app->make(Person::class); //closure() //new Person("Imron", "Lukita");
        $person2 = $this->app->make(Person::class); //closure() //new Person("Imron", "Lukita");

        self::assertEquals('Imron', $person1->firstName);
        self::assertEquals('Imron', $person2->firstName);
        self::assertNotSame($person1, $person2);
    }

    public function testSingleton()
    {

        $this->app->singleton(Person::class, function ($app){
            return new Person("Imron", "Lukita");
        });

        $person1 = $this->app->make(Person::class); //new Person("Imron", "Lukita"); if not exists
        $person2 = $this->app->make(Person::class); //return existing
        $person3 = $this->app->make(Person::class); //return existing
        $person4 = $this->app->make(Person::class); //return existing

        self::assertEquals('Imron', $person1->firstName);
        self::assertEquals('Imron', $person2->firstName);
        self::assertSame($person1, $person2); //ganti menjadi assertSame karena person1 dan 2 merupakan objct yang sama
    }

    public function testInstance()
    {
        $person = new Person("Imron", "Lukita"); //untuk inisialisasi objek dilakukan diawal
        $this->app->instance(Person::class, $person);

        $person1 = $this->app->make(Person::class); //new Person("Imron", "Lukita"); if not exists
        $person2 = $this->app->make(Person::class); //return existing
        $person3 = $this->app->make(Person::class); //return existing
        $person4 = $this->app->make(Person::class); //return existing

        self::assertEquals('Imron', $person1->firstName);
        self::assertEquals('Imron', $person2->firstName);
        self::assertSame($person1, $person2); //ganti menjadi assertSame karena person1 dan 2 merupakan objct yang sama
    }

    public function testDependencyInjection()
    {
        $this->app->singleton(Foo::class, function ($app)
        {
            return new Foo();
        });

        $foo = $this->app->make(Foo::class);
        $bar = $this->app->make(Bar::class);
        self::assertEquals("Foo and Bar", $bar->bar());
        self::assertSame($foo, $bar->foo);
    }

    public function testDependencyInjectionClosure()
    {
        $this->app->singleton(Foo::class, function ($app)
        {
            return new Foo();
        });

        $this->app->singleton(Bar::class, function ($app)
        {
            $foo = $app->make(Foo::class);
            return new Bar($app->make(Foo::class));
        });

        $foo = $this->app->make(Foo::class);
        $bar1 = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);

        self::assertNotSame($foo, $bar1);
        self::assertSame($bar1, $bar2);
    }

    public function testInterfaceToClass()
    {
        //$this->app->singleon(HalloService::class, HalloServiceIndonesia::class); //

        $this->app->singleton(HelloService::class, function($app) //Menggunakan closure
        {
            return new HelloServiceIndonesia();
        });

        $HelloService = $this->app->make(HelloService::class);

        self::assertEquals('Halo Imron', $HelloService->hello('Imron'));
    }
}
