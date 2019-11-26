<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\MigrationPackage\Migration\Schematics;

class CreateFilesRelationshipTable extends Migration {
    use Schematics;
    protected $name;
    private   $installableConfig;

    public function __construct() {
        $this->installableConfig = InstallableConfig::config('Larangular\FileManager\FileManagerServiceProvider');
        $this->connection = $this->installableConfig->getConnection('file_relationship');
        $this->name = $this->installableConfig->getName('file_relationship');
    }

    public function up() {
        $this->create(function (Blueprint $table) {
            $table->increments('id')
                  ->unsigned();
            $table->morphs('morphable');
            $table->integer('file_asset_id')
                  ->unsigned();

            if ($this->installableConfig->getTimestamp('file_relationship')) {
                $table->timestamps();
            }
        });
    }

    public function down() {
        $this->drop();
    }
}

