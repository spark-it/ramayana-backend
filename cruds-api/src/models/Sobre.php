<?php

class Sobre extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'sobre';
    protected $fillable = array('title', 'description', 'text', 'image');

    public function getImageAttribute($value)
    {
        if ($value != null) {
            return BASE_URL . '/uploads/' . $value;
        }
        return $value;
    }
}