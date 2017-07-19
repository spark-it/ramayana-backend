<?php

class Aula extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'aulas';
    protected $fillable = array('title', 'description', 'text', 'image');
}