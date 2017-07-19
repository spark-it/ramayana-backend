<?php

class Informe extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'informes';
    protected $fillable = array('title', 'description', 'text', 'image');
}