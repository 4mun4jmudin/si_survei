<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuesioner extends Model
{
    protected $table = 'kuesioner';
    protected $guarded = [];

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'kuesioner_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'kuesioner_id');
    }
}
