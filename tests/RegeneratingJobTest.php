<?php

namespace Touhidurabir\ModelUuid\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Touhidurabir\ModelUuid\Tests\App\User;
use Touhidurabir\ModelUuid\Tests\App\Profile;
use Touhidurabir\ModelUuid\Jobs\ModelUuidRegeneratorJob;
use Touhidurabir\ModelUuid\Tests\Traits\LaravelTestBootstrapping;

class RegeneratingJobTest extends TestCase {

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
    public function the_job_will_run() {

        Bus::fake();

        $user = User::create(['email' => 'mail@m.test', 'password' => '123']);
        $profile = Profile::create(['first_name' => 'First_Name', 'last_name' => 'Last_Name']);

        ModelUuidRegeneratorJob::dispatch(
            [Touhidurabir\ModelUuid\Tests\App\User::class, Touhidurabir\ModelUuid\Tests\App\Profile::class],
            true
        );

        Bus::assertDispatched(ModelUuidRegeneratorJob::class, function ($job) use ($user, $profile) {
            return true;
        });
    }


    /**
     * @test
     */
    public function the_job_can_fill_out_missing_uuid() {

        User::disbaleUuidGeneration();

        $user = User::create(['email' => 'mail@m.test', 'password' => '123']);

        $this->assertNull($user->getUuid());

        ModelUuidRegeneratorJob::dispatchNow(
            [\Touhidurabir\ModelUuid\Tests\App\User::class],
            true
        );

        $user->refresh();

        $this->assertNotNull($user->getUuid());
    }

}
