<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App\Models
 *
 * @property string id
 * @property float price
 * @property int category_id
 */
class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['id', 'price', 'category_id'];
}
