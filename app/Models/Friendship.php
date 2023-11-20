<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Friendship extends Model
{
    use HasFactory;

    protected $table = 'friendship';
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'friendID'
    ];
}
