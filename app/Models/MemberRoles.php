<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberRoles extends Model
{
    use HasFactory;
    protected $table = "member_roles";

    public function route_name(){
        return $this->hasOne('App\Models\Modules','id','module_id');
    }
}
