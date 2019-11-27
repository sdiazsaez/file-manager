<?php

namespace Larangular\FileManager\Models;

use Illuminate\Database\Eloquent\Model;
use Larangular\Installable\Facades\InstallableConfig;
use Larangular\RoutingController\Model as RoutingModel;

class FileRelationship extends Model {
    use RoutingModel;

    protected $table;
    protected $fillable = [
        'morphable_type',
        'morphable_id',
        'file_asset_id',
    ];

    protected $with = [
        'fileAsset',
    ];

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $installableConfig = InstallableConfig::config('Larangular\FileManager\FileManagerServiceProvider');
        $this->connection = $installableConfig->getConnection('file_relationship');
        $this->table = $installableConfig->getName('file_relationship');
        $this->timestamps = $installableConfig->getTimestamp('file_relationship');
    }

    public function fileAsset() {
        return $this->hasOne(FileManager::class, 'id', 'file_asset_id');
    }

}
