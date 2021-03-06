<?php

use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;

class FunctionalTest extends TestCase
{
    public $capsule;
    public $connection;
    public $carbon;

    /**
     * @var ExampleModel
     */
    public $model;

    public function setUp()
    {
        parent::setUp();

        $this->markTestSkipped(
            'Functional tests are not yet implemented.'
        );

        // Setup Eloquent and SQLite.
        $sqlite = 'tests/database/database.sqlite';
        if (!file_exists($sqlite)) {
            touch($sqlite);
        }
        $this->capsule = new Capsule();
        $this->capsule->addConnection([
            'driver'   => 'sqlite',
            'database' => $sqlite,
            'prefix'   => '',
        ], 'default');
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->connection = $this->capsule->getConnection('default');

        // Setup ExampleModel.
        $this->model = new ExampleModel();

        // Setup Carbon instance.
        $this->carbon = Carbon::now();
    }

    public function testDbConnection()
    {
    }
}
