<?php

namespace Touhidurabir\ModelUuid;

use Illuminate\Support\ServiceProvider;

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
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        $this->publishes([
            __DIR__.'/../config/model-uuid.php' => base_path('config/model-uuid.php'),
        ], 'config');
    }
    
}