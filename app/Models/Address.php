<?php


// app/Models/Address.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'street','first_name', 'last_name', 'email', 'city', 'state', 'country', 'zipcode', 'type'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
   
}

