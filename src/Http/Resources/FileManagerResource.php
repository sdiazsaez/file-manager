<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 3/18/18
 * Time: 05:18
 */

namespace Larangular\FileManager\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FileManagerResource extends Resource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return parent::toArray($request);
    }
}
