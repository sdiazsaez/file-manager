<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\MigrationPackage\Migration\Schematics;

class CreateFilesTable extends Migration {
    use Schematics;
    protected $name;
    private $installableConfig;

    public function __construct() {
        $this->installableConfig = InstallableConfig::config('Larangular\FileManager\FileManagerServiceProvider');
        $this->connection = $this->installableConfig->getConnection('file_assets');
        $this->name = $this->installableConfig->getName('file_assets');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->create(function (Blueprint $table) {
            $table->increments('id')
                  ->unsigned();
            $table->string('disk');
            $table->string('path');
            $table->string('name');
            $table->string('original_name');
            $table->string('extension');
            $table->string('type');
            $table->unsignedInteger('size')->default(0);
            $table->longText('description')
                  ->nullable();
            $table->boolean('exist');

            if (config('file-manager.uploader_model') !== false) {
                $table->unsignedInteger('uploader_id');
            }

            if ($this->installableConfig->getTimestamp('file_assets')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $this->drop();
    }
}

