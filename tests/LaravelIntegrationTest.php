<?php

namespace Touhidurabir\ModelUuid\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use Touhidurabir\ModelUuid\Tests\App\User;
use Touhidurabir\ModelUuid\Facades\ModelUuid;
use Touhidurabir\ModelUuid\Tests\App\Profile;
use Touhidurabir\ModelUuid\UuidGenerator\Generator;
use Touhidurabir\ModelUuid\ModelUuidServiceProvider;
use Touhidurabir\ModelUuid\Tests\Traits\LaravelTestBootstrapping;

/**
 *  TO-DO: Need better testing.
 *  Factories, Mocks, etc, but this does the job for now.
 */
class LaravelIntegrationTest extends TestCase {

    use LaravelTestBootstrapping;

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
    public function it_can_generate_uuid_via_facade() {

        $uuid = ModelUuid::generate();

        $this->assertIsString($uuid);
        $this->assertTrue(Str::isUuid($uuid));
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


    /**
     * @test
     */
    public function it_allow_scope_to_attach_query_clause_via_uuid() {

        $uuid = Generator::uuid4();

        User::create([
            'email'     => uniqid() . '@localhost.com',
            'password'  => bcrypt('password'),
            'uuid'      => $uuid,
        ]);

        $user = User::byUuid($uuid)->first();

        $this->assertEquals($user->uuid, $uuid);
    }


    /**
     * @test
     */
    public function it_allow_scope_to_attach_query_clause_via_array_of_uuid() {

        $uuid1 = Generator::uuid4();
        $uuid2 = Generator::uuid4();

        User::create([
            'email'     => uniqid() . '@localhost.com',
            'password'  => bcrypt('password'),
            'uuid'      => $uuid1,
        ]);

        User::create([
            'email'     => uniqid() . '@localhost.com',
            'password'  => bcrypt('password'),
            'uuid'      => $uuid2,
        ]);

        $users = User::byUuid([$uuid1, $uuid2])->get();

        $this->assertEquals($users->count(), 2);
    }


    /**
     * @test
     */
    public function it_allow_find_record_via_uuid() {

        $uuid1 = Generator::uuid4();
        $uuid2 = Generator::uuid4();

        User::create([
            'email'     => uniqid() . '@localhost.com',
            'password'  => bcrypt('password'),
            'uuid'      => $uuid1,
        ]);

        User::create([
            'email'     => uniqid() . '@localhost.com',
            'password'  => bcrypt('password'),
            'uuid'      => $uuid2,
        ]);

        $user = User::findByUuid($uuid1);
        $users = User::findByUuid([$uuid1, $uuid2]);

        $this->assertEquals($user->uuid, $uuid1);
        $this->assertEquals($users->count(), 2);
    }


    /**
     * @test
     */
    public function the_specific_model_uuid_generation_can_be_disabled_globally() {

        User::disbaleUuidGeneration();

        $user = User::create(['email' => 'mail@m.test', 'password' => '123']);
        
        $this->assertNull($user->getUuid());
    }


    /**
     * @test
     */
    public function the_specific_model_uuid_disabled_generation_can_be_enabled() {

        User::disbaleUuidGeneration();

        $user = User::create(['email' => 'mail1@m.test', 'password' => '123']);
        $this->assertNull($user->getUuid());

        User::disbaleUuidGeneration(false);

        $user = User::create(['email' => 'mail2@m.test', 'password' => '123']);
        $this->assertNotNull($user->getUuid());
    }

}