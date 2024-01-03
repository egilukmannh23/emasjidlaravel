<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;
    protected $table = 'kas';
    protected $fillable = [
        'tanggal',
        'keterangan',
        'jumlah', // Add this line to allow mass assignment for the 'tanggal' field
        'jenis', // Add this line to allow mass assignment for the 'tanggal' field
        // Other fields in your model...
    ];
}
