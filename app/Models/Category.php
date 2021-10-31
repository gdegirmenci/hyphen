<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Models
 *
 * @property int id
 * @property string name
 */
class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['id', 'name'];
}
