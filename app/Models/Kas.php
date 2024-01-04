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
    protected $casts = [
        'tanggal' => 'datetime:d-m-Y H:i'
    ];

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeSaldoAkhir($query, $masjidId = null)
    {
        $masjidId = $masjidId ?? auth()->user()->masjid_id;
        return $query->where('masjid_id', auth()->user()->masjid_id)
        ->orderBy('created_at', 'desc')
        ->value('saldo_akhir') ?? 0;
    }

    public function scopeUserMasjid($q)
    {
        return $q->where('masjid_id', auth()->user()->masjid_id);
    }
}
