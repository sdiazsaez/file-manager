<?php

namespace Larangular\FileManager\Models;

use Faker\Provider\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\RoutingController\Model as RoutingModel;
use Illuminate\Support\Facades\Storage;
use Larangular\FileManager\Facades\FileManagerController;

class FileManager extends Model {
    use RoutingModel;

    protected $table;
    protected $fillable = [
        'disk',
        'path',
        'name',
        'original_name',
        'extension',
        'type',
        'size',
        'description',
    ];

    protected $appends = [
        'url'
    ];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $this->installableConfig = InstallableConfig::config('Larangular\FileManager\FileManagerServiceProvider');
        $this->connection = $this->installableConfig->getConnection('file_assets');
        $this->table = $this->installableConfig->getName('file_assets');
        $this->timestamps = $this->installableConfig->getTimestamp('file_assets');
    }

    public static function boot() {
        parent::boot();

        static::saving(function(FileManager $fileManager){
            $fileManager->attributes['exist'] = $fileManager->fileExists();
        });
    }

    public function fileExists(): bool {
        return Storage::disk($this->attributes['disk'])
               ->exists($this->attributes['path'] . DIRECTORY_SEPARATOR . $this->attributes['name']);
    }


    public function getUrlAttribute() {
        if(!$this->attributes['exist']) {
            return;
        }

        return config('file-manager.route_prefix') . '/file/' . $this->attributes['id'] . '/' . $this->attributes['original_name'];
        //return Storage::disk($this->attributes['disk'])->get($this->attributes['path'] . DIRECTORY_SEPARATOR . $this->attributes['name']);
    }
}
