<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistAuth extends Model
{
    //
    protected $table = 'regist_auth';
    protected $primaryKey = 'aid';
    
    const CREATED_AT = 'reg_date';
    
    
}
