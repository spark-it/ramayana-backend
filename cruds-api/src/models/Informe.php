<?php

class Informe extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'informe';
    protected $fillable = array('title', 'description', 'text', 'image');
}