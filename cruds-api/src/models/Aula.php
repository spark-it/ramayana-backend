<?php

class Aula extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'aulas';
    protected $fillable = array('title', 'description', 'text', 'image');

    public function getImageAttribute($value)
    {
        return BASE_URL . '/uploads/' . $value;
    }
}