<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KreditSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $table = 'kredits';
    protected $guarded = ['id'];
}
