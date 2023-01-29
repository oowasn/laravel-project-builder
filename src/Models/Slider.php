<?php

namespace ZDSLab\Init\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model {
    use HasFactory;

    public function buttons(){
        return $this->belongsToMany(Button::class);
    }
}