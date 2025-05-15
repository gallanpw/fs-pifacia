<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // untuk JWT
use Illuminate\Support\Str; // untuk UUID
use Illuminate\Database\Eloquent\SoftDeletes; // untuk SoftDeletes

class Loan extends Model implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes; 

    protected $fillable = ['funder_id', 'farmer_id', 'data', 'is_active', 'attachment_url'];

    protected $keyType = 'string'; // Menggunakan UUID sebagai string
    public $incrementing = false; // Menonaktifkan auto-increment
    protected $primaryKey = 'id'; // Pastikan primary key adalah id
    protected $dates = ['deleted_at']; // Menambahkan kolom deleted_at untuk soft delete

    protected $casts = [
        'data' => 'array', // Mengkonversi field JSON menjadi array
    ];

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

    // Relasi dengan funder dan farmer
    public function funder()
    {
        return $this->belongsTo(Funder::class);
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    /* supaya otomatis ikut di‑JSON‑kan */
    protected $appends = ['funder_name', 'farmer_name'];

    public function getFunderNameAttribute()
    {
        return optional($this->funder)->name;
    }

    public function getFarmerNameAttribute()
    {
        return optional($this->farmer)->name;
    }
}
