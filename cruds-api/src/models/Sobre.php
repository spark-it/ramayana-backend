<?php

class Sobre extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'sobre';
    protected $fillable = array('title', 'description', 'text', 'image');
}