<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_model extends Model
{
    use HasFactory;
    protected $fillable = ['id','name'];
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
}
