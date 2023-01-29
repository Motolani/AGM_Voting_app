<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    protected $table = 'votes';
    protected $fillable = [
        'user_id', 'item_id', 'votes', 'user_remaining_votes',
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
