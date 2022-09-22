<?php

namespace App\Providers;

use App\Models\GameResult;
use App\Models\Question;
use App\Models\Tag;
use App\Observers\GameResultObserver;
use App\Observers\QuestionObserver;
use App\Observers\TagObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // On active les hook concernant les modèles qui ont des relations avec Elasticsearch
        Question::observe(QuestionObserver::class);
        Tag::observe(TagObserver::class);
        GameResult::observe(GameResultObserver::class);
    }
}
