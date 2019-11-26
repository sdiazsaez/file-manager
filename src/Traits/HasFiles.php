<?php

namespace Larangular\FileManager\Traits;

use Larangular\FileManager\Models\FileRelationship;

trait HasFiles {

    public function files() {
        return $this->morphMany(FileRelationship::class, 'morphable');
    }
}
