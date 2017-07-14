<?php

class Crud2 extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'crud2';
    protected $fillable = array('title', 'description', 'text', 'image');
}