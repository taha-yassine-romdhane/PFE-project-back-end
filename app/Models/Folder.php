<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','folder_state','file_state','file',  'id_category','id_user'  ];
    public function users()
    {
        return $this->belongsTo(user::class);
    }
    public function historys()
    {
        return $this->belongsTo(folder::class);
    }
    public function category_models()
    {
        return $this->hasMany(category_model::class);
    }
}
