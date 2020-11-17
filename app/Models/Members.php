<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    use HasFactory;
    protected $table = "members";


    public function member_type_name(){
        return $this->hasOne('App\Models\MemberType','id','member_type');
    }
}
