<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'Posts';

    public function person() {
        return $this->belongsTo("App\Person");
    }
}
