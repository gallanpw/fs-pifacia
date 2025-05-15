<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // untuk JWT
use Illuminate\Support\Str; // untuk UUID
use Illuminate\Database\Eloquent\SoftDeletes; // untuk SoftDeletes

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    // Menentukan kolom yang akan diubah ke UUID
    protected $keyType = 'string'; // Menetapkan tipe kunci sebagai string
    public $incrementing = false; // Disable auto-increment
    protected $primaryKey = 'id'; // Pastikan primary key adalah id

    protected $casts = [
        'data' => 'array', // Mengkonversi field JSON menjadi array
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Menambahkan role ke dalam fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
        static::creating(function ($user) {
            // Jika id belum ada, buat UUID
            if (!$user->id) {
                $user->id = (string) Str::uuid(); // Generate UUID
            }
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class); // Relasi dengan role
    }

    /* supaya otomatis ikut di‑JSON‑kan */
    protected $appends = ['role_name'];

    public function getRoleNameAttribute()
    {
        return optional($this->role)->name;
    }
}
