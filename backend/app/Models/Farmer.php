<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // untuk JWT
use Illuminate\Support\Str; // untuk UUID
use Illuminate\Database\Eloquent\SoftDeletes; // untuk SoftDeletes

class Farmer extends Model implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['data', 'is_active', 'attachment_url'];

    protected $keyType = 'string'; // Menggunakan UUID sebagai string
    public $incrementing = false; // Menonaktifkan auto-increment
    protected $primaryKey = 'id'; // Pastikan primary key adalah id
    protected $dates = ['deleted_at']; // Menambahkan kolom deleted_at untuk soft delete

    protected $casts = [
        'data' => 'array', // Mengkonversi field JSON menjadi array
    ];

    // accessor biar gampang ambil nama
    public function getNameAttribute()
    {
        return $this->data['name'] ?? null;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Inisialisasi UUID saat pembuatan record baru.
     */
    protected static function booted()
    {
        static::creating(function ($role) {
            if (!$role->id) {
                $role->id = (string) Str::uuid(); // Generate UUID untuk kolom id
            }
        });
    }
}
