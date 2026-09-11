<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    private static bool $isResolvingAuth = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('hide_developer', function (Builder $builder) {
            // Prevent infinite recursion when Auth tries to resolve the user
            if (self::$isResolvingAuth) {
                return;
            }

            self::$isResolvingAuth = true;
            $authenticatedId = null;
            try {
                if (app()->bound('auth')) {
                    $authenticatedId = auth()->id();
                }
            } finally {
                self::$isResolvingAuth = false;
            }

            $builder->where(function ($query) use ($authenticatedId) {
                $query->where('is_developer', false);
                
                if ($authenticatedId) {
                    $query->orWhere('id', $authenticatedId);
                }
            });
        });


    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'satker_id',
        'username',
        'no_hp',
        'otp_email',
        'topup_password',
        'last_activity_at',
        'last_latitude',
        'last_longitude',
        'is_active',
        'profile_photo_path',
        'is_developer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'topup_password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_activity_at' => 'datetime',
        'is_active' => 'boolean',
        'is_developer' => 'boolean',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function isOnline()
    {
        return \Illuminate\Support\Facades\Cache::has('user-is-online-' . $this->id);
    }

    public function personel()
    {
        return $this->hasOne(Personel::class);
    }
    public function getRoleLabelAttribute()
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'kasubbag' => 'Kasubbag',
            'admin_satker' => 'Admin Satker',
            'petugas_bbm' => 'Petugas BBM',
            'personel' => 'Personel',
            default => ucfirst($this->role),
        };
    }

    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
