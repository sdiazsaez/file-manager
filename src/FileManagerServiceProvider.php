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
        $this->loadRoutesFrom(__DIR__ . '/Http/routes/web.php');
        $this->declareMigrationGlobal();
        $this->declareMigrationFileAssets();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->commands(FileManager::class);
    }

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
        return new Installer(__CLASS__);
    }

    private function declareMigrationGlobal(): void {
        $this->declareMigration([
            'connection'   => 'mysql',
            'migrations'   => [
                'local_path' => base_path() . '/vendor/larangular/file-manager/database/migrations',
                //__DIR__ . '/../database/migrations',
            ],
            'seeds'        => [
                'local_path' => __DIR__ . '/../database/seeds',
            ],
            'seed_classes' => [
            ],
        ]);
    }

    private function declareMigrationFileAssets() {
        $this->declareMigration([
            'name'      => 'file_assets',
            'timestamp' => true,
        ]);
    }
}
