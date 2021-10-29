<?php

namespace Touhidurabir\ModelUuid;

use Illuminate\Support\ServiceProvider;
use Touhidurabir\ModelUuid\UuidGenerator\Generator;
use Touhidurabir\ModelUuid\Console\RegenerateModelUuid;

class ModelUuidServiceProvider extends ServiceProvider {
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

        $this->mergeConfigFrom(
            __DIR__.'/../config/model-uuid.php', 'model-uuid'
        );

        $this->app->bind('model-uuid', function () {
            
            return new Generator;
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        if ( $this->app->runningInConsole() ) {
			$this->commands([
				RegenerateModelUuid::class
			]);
		}

        $this->publishes([
            __DIR__.'/../config/model-uuid.php' => base_path('config/model-uuid.php'),
        ], 'config');
    }
    
}