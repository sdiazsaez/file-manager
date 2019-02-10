<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 2019-01-06
 */

namespace Larangular\FileManager\Facades;

use Illuminate\Support\Facades\Facade;

class FileManagerController extends Facade {
    protected static function getFacadeAccessor() {
        return 'FileManagerController';
    }
}
