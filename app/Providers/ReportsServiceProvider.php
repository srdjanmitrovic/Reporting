<?php 
namespace App\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ReportsServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Reporting\RepositoryInterface',
            'App\Reporting\TransactionRepository'
        );
    }
}