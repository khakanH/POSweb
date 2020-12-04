<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembersCompany extends Model
{
    use HasFactory;
    protected $table = "members_company";


     public function company_name(){
        return $this->hasOne('App\Models\CompanyInfo','id','company_id');
    }
}
