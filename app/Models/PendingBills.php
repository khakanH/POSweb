<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingBills extends Model
{
    use HasFactory;
    protected $table = "pending_bills";

    public function customer_name(){
        return $this->hasOne('App\Models\Customers','id','customer_id');
    }
}
