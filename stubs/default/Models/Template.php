<?php

namespace ZDSLab\Init\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model {
    use HasFactory;

    public function pages(){
        return $this->hasMany(Page::class);
    }
}