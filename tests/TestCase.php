<?php

use Zaichaopan\AccessGranted\AccessGrantedServiceProvider;

abstract class TestCase extends Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [AccessGrantedServiceProvider::class];
    }

    public function setUp()
    {
        parent::setUp();
        Eloquent::unguard();
        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__ . '/../migrations'),
        ]);
    }

    public function tearDown()
    {
        \Schema::drop('users');
        \Schema::drop('roles');
        \Schema::drop('permissions');
        \Schema::drop('permission_role');
        \Schema::drop('role_user');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('view.paths', [__DIR__ . '/stubs/resources/views']);

        \Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('email');
            $table->timestamps();
        });
    }
}
