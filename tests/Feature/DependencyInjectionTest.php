<?php 

namespace Tests\Feature;

use App\Data\Bar;
use App\Data\Foo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DependencyInjectionTest extends TestCase {
    public function testDependencyInjection() 
    {
        $foo=new Foo();

        //Constractor
        $bar=new Bar($foo); //Wajib memasukan data "Foo", karena melakukan Injection dari data "Foo" ke dalam "Bar"
        //Function
        //        $bar->setFoo($foo);
        //Atrribute
        //       $bar->forr = $foo;

        self::assertEquals('Foo and Bar', $bar->bar());
    }
}

?>