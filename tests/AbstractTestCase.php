<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2019-01-05
 */

namespace Larangular\UnidadFomento\Tests;

use Larangular\UFScraper\UFScraperServiceProvider;
use Larangular\UnidadFomento\FileManagerServiceProvider;
use Orchestra\Testbench\TestCase;

class AbstractTestCase extends TestCase {

    protected function setUp()
    {
        parent::setUp();

        //$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        //$this->artisan('migrate', ['--database' => 'package_test'])->run();
        //$this->loadLaravelMigrations(['--database' => 'package_test']);
        // and other test setup steps you need to perform
    }

    protected function getEnvironmentSetUp($app) {
        $app['config']->set('uf-scraper', require(__DIR__ . '/../vendor/larangular/uf-scraper/config/uf-scraper.php'));
        $app['config']->set('unidad-fomento', require(__DIR__ . '/../config/unidad-fomento.php'));

        $connectionName = config('unidad-fomento.connection');
        $app['config']->set('database.default', $connectionName);
        $app['config']->set('database.connections.'.$connectionName, [
            'driver'   => 'sqlite',
            'database' => __DIR__ . '/db.sqlite',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app) {
        return [
            UFScraperServiceProvider::class,
            FileManagerServiceProvider::class,
        ];
    }

}
