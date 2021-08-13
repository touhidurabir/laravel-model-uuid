<?php

namespace Touhidurabir\ModelUuid\Tests;

use Orchestra\Testbench\TestCase;
use Touhidurabir\ModelUuid\Facades\ModelUuid;
use Touhidurabir\ModelUuid\ModelUuidServiceProvider;
use Touhidurabir\ModelUuid\Tests\App\User;
use Touhidurabir\ModelUuid\Tests\App\Profile;
use Touhidurabir\ModelUuid\UuidGenerator\Generator;

/**
 *  TO-DO: Need better testing.
 *  Factories, Mocks, etc, but this does the job for now.
 */
class LaravelIntegrationTest extends TestCase {

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


    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations() {

        $this->loadMigrationsFrom(__DIR__ . '/App/database/migrations');
        
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback', ['--database' => 'testbench'])->run();
        });
    }


    /**
     * @test
     */
    public function it_add_an_uuid_on_creation() {

        $user = User::create([
            'email'    => uniqid() . '@localhost.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertIsString($user->uuid);
    }


    /**
     * @test
     */
    public function it_attach_unique_uuid_on_each_creation() {

        $user1 = User::create([
            'email'    => uniqid() . '@localhost.com',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::create([
            'email'    => uniqid() . '@localhost.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertNotEquals($user1->uuid, $user2->uuid);
    }


    /**
     * @test
     */
    public function it_do_not_override_uuid_if_already_attach_on_creation() {

        $uuid = Generator::uuid4();

        $user = User::create([
            'email'     => uniqid() . '@localhost.com',
            'password'  => bcrypt('password'),
            'uuid'      => $uuid,
        ]);

        $this->assertEquals($user->uuid, $uuid);
    }

    
    /**
     * @test
     */
    public function it_do_not_attach_any_uuid_if_no_uuidable_column_attached_to_model() {

        $profile = Profile::create([
            'first_name' => 'First_Name',
            'last_name'  => 'Last_Name'
        ]);

        $this->assertNull($profile->uuid);
    }

}