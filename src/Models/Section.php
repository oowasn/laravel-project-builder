<?php

namespace Zdslab\Laravelinit\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model {
    use HasFactory;

    public  function pages(){
        return $this->belongsToMany(Page::class, 'page_sections');
    }
}