<?php

namespace Touhidurabir\ModelUuid\Tests;

use Orchestra\Testbench\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Illuminate\Support\Collection;
use Touhidurabir\ModelUuid\Tests\App\User;
use Touhidurabir\ModelUuid\Tests\App\Profile;
use Touhidurabir\ModelUuid\Console\RegenerateModelUuid;
use Touhidurabir\ModelUuid\Jobs\ModelUuidRegeneratorJob;
use Touhidurabir\ModelUuid\Tests\Traits\LaravelTestBootstrapping;

class RegenerationCommandTest extends TestCase {

    use LaravelTestBootstrapping;

    /**
     * The testable dummy command
     *
     * @var object<\Symfony\Component\Console\Tester\CommandTester>
     */
    protected $regenerationCommand;


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
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void {

        parent::setUp();

        $this->configureTestCommand();
    }


    protected function configureTestCommand() {

        $command = new RegenerateModelUuid;
        $command->setLaravel($this->app);

        $this->regenerationCommand = new CommandTester($command);
    }


    /**
     * @test
     */
    public function the_command_will_run() {

        $status = $this->regenerationCommand->execute([
            'models' => 'Touhidurabir\\ModelUuid\\Tests\\App\\User,Touhidurabir\\ModelUuid\\Tests\\App\\Profile',
        ]);

        $this->assertEquals($status, 0);

        $status = $this->regenerationCommand->execute([
            'models' => 'User,Profile',
            '--path' => 'Touhidurabir\\ModelUuid\\Tests\\App\\',
        ]);

        $this->assertEquals($status, 0);
    }


    /**
     * @test
     */
    public function the_command_will_fail_given_wrong() {

        $status = $this->regenerationCommand->execute([
            'models' => 'Touhidurabir\\ModelUuid\\Tests\\App\\Testing,',
        ]);

        $this->assertEquals($status, 1);
    }

    /**
     * @test
     */
    public function the_command_will_run_properly_with_provided_job() {

        $status = $this->regenerationCommand->execute([
            'models' => 'Touhidurabir\\ModelUuid\\Tests\\App\\User,Touhidurabir\\ModelUuid\\Tests\\App\\Profile',
            '--job'  => 'Touhidurabir\\ModelUuid\\Jobs\\ModelUuidRegeneratorJob'
        ]);

        $this->assertEquals($status, 0);        
    }


    /**
     * @test
     */
    public function the_command_will_fail_given_wrong_job() {

        $status = $this->regenerationCommand->execute([
            'models' => 'Touhidurabir\\ModelUuid\\Tests\\App\\User,Touhidurabir\\ModelUuid\\Tests\\App\\Profile',
            '--job'  => 'Touhidurabir\\ModelUuid\\NonExistingJob'
        ]);

        $this->assertEquals($status, 1);        
    }


    /**
     * @test
     */
    public function the_command_will_run_set_proper_uuid_for_missing_rows() {

        User::disbaleUuidGeneration();

        $user = User::create(['email' => 'mail@m.test', 'password' => '123']);

        $this->assertNull($user->getUuid());

        $status = $this->regenerationCommand->execute([
            'models' => 'Touhidurabir\\ModelUuid\\Tests\\App\\User',
        ]);

        $this->assertEquals($status, 0);

        $user->refresh();

        $this->assertNotNull($user->getUuid());
    }

}
