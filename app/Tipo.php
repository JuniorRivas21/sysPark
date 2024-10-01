<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'tipos';
    protected $fillable = ['descripcion'];

    //protected $rules = ['descripcion' => 'required|min:4'];
}
