<?php

namespace Larangular\FileManager\Models;

use Faker\Provider\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
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
        $this->table = config('installable.migrations.Larangular\FileManager\FileManagerServiceProvider.file-assets.name');
        $this->connection = config('installable.migrations.Larangular\FileManager\FileManagerServiceProvider.file-assets.connection');
        $this->timestamps = config('installable.migrations.Larangular\FileManager\FileManagerServiceProvider.file-assets.timestamp');
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
    /*
    public function setFileAttribute(UploadedFile $file) {
        $this->attributes['file'] = $file;

        $fileResponse = FileManagerController::uploadFile($file, @$this->attributes['disk']);
        dump([
            $file,
            $fileResponse,
            $this->attributesToArray()
        ]);

    }*/
}
