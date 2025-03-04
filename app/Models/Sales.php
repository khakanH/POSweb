<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $table = "sales";


    public function customer_name(){
        return $this->hasOne('App\Models\Customers','id','customer_id');
    }

    public function payment_method_name(){
        return $this->hasOne('App\Models\PaymentMethods','id','payment_method');
    }
}
