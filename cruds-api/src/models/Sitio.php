<?php

class Sitio extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'sitios';
    protected $fillable = array('title', 'description', 'text', 'image');

    public function getImageAttribute($value)
    {
        return BASE_URL . '/uploads/' . $value;
    }
}