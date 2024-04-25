<?php

namespace App\Providers;

use App\Services\ElasticService;
use Illuminate\Support\ServiceProvider;

class ElasticServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Au lancement de l'app, on créé une instance d'Elastic pour définir les infos de connexion de celle-ci
        $this->app->singleton(ElasticService::class, function () {
            return new ElasticService(env('ELASTIC_HOST'), env('ELASTIC_PORT'), env('ELASTIC_USER'), env('ELASTIC_PASSWORD'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
