<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meta extends Model
{
    use SoftDeletes;
    protected $table = 'meta';

    protected $primaryKey = 'id';

    protected $casts = [
        'data' => 'object',
    ];

    public function getDescription() {
        return [
            'data' => 'json'
        ];
    }
}
