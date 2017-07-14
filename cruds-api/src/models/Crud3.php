<?php

class Crud3 extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'crud3';
    protected $fillable = array('title', 'description', 'text', 'image');
}