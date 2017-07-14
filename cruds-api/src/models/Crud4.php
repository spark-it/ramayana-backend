<?php

class Crud4 extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'crud4';
    protected $fillable = array('title', 'description', 'text', 'image');
}