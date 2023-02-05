<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model {

    use HasFactory;

    protected $fillable = [
        'name',
        'view',
    ];

    public function pages(){
        return $this->hasMany(Page::class);
    }
}