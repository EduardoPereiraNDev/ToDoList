<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',       
        'description',   
        'expiryDate',    
        'completionDate', 
        'user_id' 
    ];

    public function user()
    {
        return $this->belongsTo(User::class); 
    }
}
