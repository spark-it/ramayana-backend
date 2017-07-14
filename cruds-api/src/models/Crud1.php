<?php
class Crud1 extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'crud1';
    protected $fillable = array('title', 'description', 'text', 'image');
}