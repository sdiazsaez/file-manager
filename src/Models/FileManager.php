<?php

namespace Larangular\FileManager\Models;

use Faker\Provider\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\RoutingController\CachableModel as RoutingModel;
use Illuminate\Support\Facades\Storage;
use Larangular\FileManager\Facades\FileManagerController;
use Jedrzej\Searchable\SearchableTrait;

class FileManager extends Model {
    use SoftDeletes, RoutingModel, SearchableTrait;

    public $searchable = ['*'];
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
        'url',
    ];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $installableConfig = InstallableConfig::config('Larangular\FileManager\FileManagerServiceProvider');
        $this->connection = $installableConfig->getConnection('file_assets');
        $this->table = $installableConfig->getName('file_assets');
        $this->timestamps = $installableConfig->getTimestamp('file_assets');
    }

    public static function boot() {
        parent::boot();
        static::saving(static function (FileManager $fileManager) {
            $fileManager->attributes['exist'] = $fileManager->fileExists();
        });
    }

    public function fileExists(): bool {
        return Storage::disk($this->attributes['disk'])
                      ->exists($this->attributes['path'] . DIRECTORY_SEPARATOR . $this->attributes['name']);
    }

    public function getUrlAttribute() {
        if (!$this->attributes['exist']) {
            return;
        }

        return config('app.url') . config('file-manager.route_prefix') . '/file/' . $this->attributes['id'] . '/' . $this->attributes['original_name'];
        //return Storage::disk($this->attributes['disk'])->get($this->attributes['path'] . DIRECTORY_SEPARATOR . $this->attributes['name']);
    }

}
