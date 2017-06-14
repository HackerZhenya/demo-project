<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'Persons';

    public function posts() {
        return $this->hasMany("App\Post");
    }
}
