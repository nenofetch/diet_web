<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'name',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'work',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function sentConsultations()
    {
        return $this->hasMany(Consultation::class, 'sender_id');
    }

    public function receivedConsultations()
    {
        return $this->hasMany(Consultation::class, 'recipient_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function educationHistoriesActivities()
    {
        return $this->hasMany(EducationHistoryActivity::class);
    }
}
