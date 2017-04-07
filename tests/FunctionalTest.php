<?php

use SKAgarwal\Reflection\ReflectableTrait;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;
// use Illuminate\Events\Dispatcher;
// use Illuminate\Container\Container;

class FunctionalTest extends \PHPUnit\Framework\TestCase
{
    public $capsule;
    public $connection;
    public $carbon;

    public function setUp()
    {
        // Setup Eloquent and SQLite.
        $sqlite = 'tests/database/database.sqlite';
        if (! file_exists($sqlite)) {
            touch($sqlite);
        }
        $this->capsule = new Capsule();
        $this->capsule->addConnection([
            'driver' => 'sqlite',
            'database' => $sqlite,
            'prefix' => '',
        ], 'default');
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->connection = $this->capsule->getConnection('default');

        // Setup ExampleModel.
        $this->reflect(new ExampleModel);

        // Setup Carbon instance.
        $this->carbon = Carbon::now();
    }
}
