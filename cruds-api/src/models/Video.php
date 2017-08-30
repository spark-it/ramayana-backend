<?php

class video extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'videos';
    protected $fillable = array('title', 'description', 'text', 'category', 'video_link', 'thumb');
}