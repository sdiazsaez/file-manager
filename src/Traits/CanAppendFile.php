<?php

namespace Larangular\FileManager\Traits;

use Illuminate\Http\Request;
use Larangular\FileManager\Http\Controllers\FileRelationship\Gateway as FRelationship;

trait CanAppendFile {

    public function appendFile(Request $request, int $id) {
        $model = parent::getEntry($id);

        $data = array_merge($request->all(), [
            'morphable_type' => $this->model,
            'morphable_id' => $id,
        ]);


        //$model->files()->create($request->all());

        $relationship = new FRelationship();
        $saved = $relationship->save($data);

        dd([
            'appendFile',
            $relationship,
            $saved
        ]);

        return parent::entry($id);
    }

}
