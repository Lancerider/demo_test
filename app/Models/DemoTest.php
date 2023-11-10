<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoTest extends Model
{
    protected $table = "demo_test";
    protected $fillable = ['ref', 'name', 'description'];
}
