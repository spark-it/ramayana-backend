<?php
class Texto extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'textos';
    protected $fillable = array('title', 'description', 'text', 'image');
}