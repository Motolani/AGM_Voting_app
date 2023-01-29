<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
    { 
        protected $table = 'admins';
        protected $fillable = [
            'name', 'email', 'password','user_id',
        ];
        protected $hidden = [
            'password', 'remember_token',
        ];
        public function user()
    {
        return $this->belongsTo(User::class);
    }
}
