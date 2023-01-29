<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'name', 'total_votes','description','status'
    ];
    
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    public function item()
    {
        return $this->belongsTo(Meeting::class);
    }
}
