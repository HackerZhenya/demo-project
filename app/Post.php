<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use Symfony\Component\Debug\Exception\FatalErrorException;

class Post extends Model
{
    protected $table = 'Posts';

    public function person() {
        return $this->belongsTo("App\Person");
    }

    public function scopeLatest($query) {
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeIdSince($query, $id) {
        if (!is_numeric($id)) return $query;
        return $query->where('id', '>=', (int)$id);
    }

    public function scopeIdBefore($query, $id) {
        if (!is_numeric($id)) return $query;
        return $query->where('id', '<=', (int)$id);
    }

    public function scopeHeadContains($query, $word) {
        return $query->where('head', 'LIKE', "%$word%");
    }

    public function scopeBodyContains($query, $word) {
        return $query->where('body', 'LIKE', "%$word%");
    }

    public function scopePublished($query, $state) {
        if ($state !== true) return $query;
        return $query->where('published', true);
    }
}

