<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Folder;

class PdfFile extends Model
{
    protected $fillable = ['id','filename', 'file_path', 'file_data']; // Updated $fillable property

    use HasFactory;

    public function folder()
    {
        return $this->belongsTo( Folder::class );
    }
      // Define the relationship with the Image model
      public function images()
      {
          return $this->hasMany(Image::class, 'pdf_id');
      }
}
