<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['pdf_id', 'path', 'page_number'];

    public function pdf()
    {
        return $this->belongsTo(PdfFile::class);
    }
}
