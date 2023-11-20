<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Group extends Model
{
    use HasFactory;

    public $timestamps  = false;
    public $primaryKey = 'groupID';

    public Function owner() {
        return User::find($this->creatorID);
    }
}
