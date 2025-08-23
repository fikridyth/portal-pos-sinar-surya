<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockSecond extends Model
{
    use HasFactory;

    protected $connection = 'mysql_second';
    protected $table = 'product_stocks';
    protected $guarded = ['id'];
}
