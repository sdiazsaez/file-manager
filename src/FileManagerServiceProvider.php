<?php

namespace Larangular\FileManager;


use Larangular\FileManager\Commands\FileManager;
use Larangular\Installable\Support\{InstallableServiceProvider as ServiceProvider, PublisableGroups, PublishableGroups};
use Larangular\UFScraper\UFScraperServiceProvider;
use Larangular\UnidadFomento\Commands\UnidadFomento;
use Larangular\Installable\{Contracts\HasInstallable,
    Contracts\Installable,
    Installer\Installer};


class FileManagerServiceProvider extends ServiceProvider implements HasInstallable {

    protected $defer = false;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/Http/Routes/FileManagerRoutes.php');
        /*
        $this->publishes([
                             __DIR__ . '/../config/file-manager.php' => config_path('file-manager.php')
                         ], FileManagerServiceProvider::class . '.config');

        $this->publishes([
                             __DIR__ . '/database/migrations' => database_path('migrations/unidad-fomento')
                         ], FileManagerServiceProvider::class . '.migrations');
        $this->publishes([
                             __DIR__ . '/database/seeds' => database_path('seeds/unidad-fomento'),
                         ], FileManagerServiceProvider::class . '.seeds');
        */

        $this->publishesType([
            __DIR__ . '/../config/file-manager.php' => config_path('file-manager.php'),
        ], PublishableGroups::Config);

        $this->declareMigration([
            'name'       => 'file-assets',
            'connection' => 'mysql',
            'timestamp'  => true,
            'local_path' => __DIR__ . '/database/migrations',
            'publish_path' => database_path('migrations/file-assets')
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->commands(FileManager::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(__DIR__ . '/../config/file-manager.php', 'file-manager');


        $this->app->singleton('FileManagerController', function(){
            return new Http\Controllers\FileManager\FileManagerController();
        });
    }

    public function provides() {
        return ['FileManagerController'];
    }

    public function installer(): Installable {
        return new Installer(FileManagerServiceProvider::class);
    }
}
