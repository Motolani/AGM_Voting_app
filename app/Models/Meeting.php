<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $table = 'meetings';
    protected $fillable = [
        'title', 'item_id', 'date',
    ];
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
