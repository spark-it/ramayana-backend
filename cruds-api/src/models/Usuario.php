<?php

class Usuario extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'usuarios';
    protected $fillable = array('first_name', 'last_name', 'facebook_id');
}