<?php

class Sobre extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'sobre';
    protected $fillable = array('title', 'description', 'text', 'image');

    public function getImageAttribute($value)
    {
        return BASE_URL . '/uploads/' . $value;
    }
}