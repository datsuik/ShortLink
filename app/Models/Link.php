<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Link extends Model {

    protected $table = 'links';

    public $timestamps = true;

    protected $fillable = [
        'link',
        'token',
        'url_limit',
        'url_limit_left',
        'url_time'
    ];   
 
 

    public function setUrlTimeAttribute($value) {
        $this->attributes['url_time'] = ($value > 24) ? 24 : $value;
    }


    public function uniqueStr() {
        $uniqueStr = Str::random(8);
        while(self::where('token', $uniqueStr)->exists()) {
            $uniqueStr = Str::random(8);
        }
        return $uniqueStr;
    }
 
}
