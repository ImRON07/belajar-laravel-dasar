<?php

namespace Tests\Feature;

use App\Data\Foo;
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
            return new Person("Yapet", "Lukita");
        });

        $person1 = $this->app->make(Person::class); //closure() //new Person("Yapet", "Lukita");
        $person2 = $this->app->make(Person::class); //closure() //new Person("Yapet", "Lukita");

        self::assertEquals('Yapet', $person1->firstName());
        self::assertEquals('Yapet', $person2->firstName());
        self::assertNotSame($person1, $person2);
    }

    public function testSingleton()
    {

        $this->app->singleton(Person::class, function ($app){
            return new Person("Yapet", "Lukita");
        });

        $person1 = $this->app->make(Person::class); //new Person("Yapet", "Lukita"); if not exists 
        $person2 = $this->app->make(Person::class); //return existing
        $person3 = $this->app->make(Person::class); //return existing
        $person4 = $this->app->make(Person::class); //return existing

        self::assertEquals('Yapet', $person1->firstName());
        self::assertEquals('Yapet', $person2->firstName());
        self::assertSame($person1, $person2); //ganti menjadi assertSame karena person1 dan 2 merupakan objct yang sama
    }

    public function testInstance()
    {
        $person = new Person("Yapet", "Lukita");
        $this->app->instanace(Person::class, $person);

        $person1 = $this->app->make(Person::class); //new Person("Yapet", "Lukita"); if not exists 
        $person2 = $this->app->make(Person::class); //return existing
        $person3 = $this->app->make(Person::class); //return existing
        $person4 = $this->app->make(Person::class); //return existing

        self::assertEquals('Yapet', $person1->firstName());
        self::assertEquals('Yapet', $person2->firstName());
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

        self::assertSame($foo, $bar);
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
            return new Bar();
        });

        $foo = $this->app->make(Foo::class);
        $bar1 = $this->app->make(Bar::class);
        $bar2 = $this->app->make(Bar::class);
        
        self::assertSame($foo, $bar);
        self::assertSame($bar1, $bar2);
    }

    public function testInterfaceToClass()
    {
        //$this->app->singleon(HalloService::class, HalloServiceIndonesia::class); //

        $this->app->singleton(HalloService::class, function($app) //Menggunakan closure
        {
            return new HalloServiceIndonesia();
        });

        $this->app->make(HalloService::class);

        self::assertEquals('Halo Yapet', $halloService->hallo('Yapet'));
    }
}
