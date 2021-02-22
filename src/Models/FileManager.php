<?php

namespace Larangular\FileManager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jedrzej\Searchable\SearchableTrait;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\RoutingController\CachableModel as RoutingModel;

class FileManager extends Model {
    use SoftDeletes, RoutingModel, SearchableTrait;

    public    $searchable = ['*'];
    protected $table;
    protected $fillable   = [
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

        static::forceDeleted(static function (FileManager $fileManager) {
            $fileManager->deleteFile();
        });

    }

    public function fileExists(): bool {
        return Storage::disk($this->attributes['disk'])
                      ->exists($this->pathInDisk());
    }

    public function setOriginalNameAttribute(string $value): void {
        $this->attributes['original_name'] = str_replace([
            ' ', '$', '% ', '#', '<', '>', '|', '/'
        ], '_', $value);
    }

    public function getUrlAttribute() {
        if (!$this->attributes['exist']) {
            return;
        }

        return Str::finish(config('file-manager.url'),
                '/') . config('file-manager.route_prefix') . '/file/' . $this->attributes['id'] . '/' . $this->attributes['original_name'];
    }

    public function fullPath(): string {
        return Storage::disk($this->attributes['disk'])
                      ->path($this->pathInDisk());
    }

    protected function deleteFile(): void {
        if ($this->fileExists()) {
            Storage::disk($this->attributes['disk'])
                   ->delete($this->pathInDisk());
        }
    }

    protected function pathInDisk(): string {
        $path = Str::finish($this->attributes['path'], DIRECTORY_SEPARATOR) . $this->attributes['name'];
        return Str::startsWith($path, DIRECTORY_SEPARATOR)
            ? Str::replaceFirst(DIRECTORY_SEPARATOR, '', $path)
            : $path;
    }
}
