<?php

namespace App\Models;



use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Equipe extends Authenticatable
{
    use Notifiable;

    protected $table = 'equipe';
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nom',
        'mail',
        'pwd',
    ];

    protected $hidden = [
        'pwd'
    ];

    public function getAuthPassword()
    {
        return  $this->pwd;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['pwd'] = Hash::make($value);
    }

}
