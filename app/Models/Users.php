<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable; // Import the Authenticatable interface
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Users extends Model implements Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use \Illuminate\Auth\Authenticatable; // Use the Authenticatable trait

    protected $table='user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'Current_address',
        'is_admin',
        'gender',
    ];

    public function orders()
    {

        return $this->hasMany(Order::class);

    }
}

