<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'Folder_id'];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function boxes()
    {
        return $this->hasMany(Box::class);
    }
}

