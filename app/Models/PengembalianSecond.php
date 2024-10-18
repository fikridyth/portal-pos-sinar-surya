<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengembalianSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $table = 'pengembalians';
    protected $guarded = ['id'];
}
