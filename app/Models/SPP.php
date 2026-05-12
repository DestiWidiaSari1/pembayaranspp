<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SPP extends Model
{
    protected $table = 'spps';
    protected $fillable = ['kode', 'tingkat', 'nominal'];
}