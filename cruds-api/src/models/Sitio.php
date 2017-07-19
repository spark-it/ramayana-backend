<?php

class Sitio extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'sitios';
    protected $fillable = array('title', 'description', 'text', 'image');
}