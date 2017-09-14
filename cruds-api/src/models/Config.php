<?php

class Config extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'config';
    protected $fillable = array('key', 'value');
}
