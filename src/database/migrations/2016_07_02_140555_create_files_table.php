<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Larangular\MigrationPackage\Migration\Schematics;

class CreateFilesTable extends Migration {
    use Schematics;
    protected $name;

    public function __construct() {
        $this->name = config('installable.migrations.Larangular\FileManager\FileManagerServiceProvider.file-assets.name');
        $this->connection = config('installable.migrations.Larangular\FileManager\FileManagerServiceProvider.file-assets.connection');
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

            if (config('installable.migrations.Larangular\FileManager\FileManagerServiceProvider.file-assets.timestamp')) {
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

