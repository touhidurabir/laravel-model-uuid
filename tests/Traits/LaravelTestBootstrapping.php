<?php

namespace Touhidurabir\ModelUuid\Tests\Traits;

use Touhidurabir\ModelUuid\Facades\ModelUuid;
use Touhidurabir\ModelUuid\ModelUuidServiceProvider;

trait LaravelTestBootstrapping {

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app) {

        return [
            ModelUuidServiceProvider::class,
        ];
    }   
    
    
    /**
     * Override application aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app) {
        
        return [
            'ModelUuid' => ModelUuid::class,
        ];
    }


    /**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application $app
     * @return void
     */
    protected function defineEnvironment($app) {

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('app.url', 'http://localhost/');
        $app['config']->set('app.debug', false);
        $app['config']->set('app.key', env('APP_KEY', '1234567890123456'));
        $app['config']->set('app.cipher', 'AES-128-CBC');
    }

}