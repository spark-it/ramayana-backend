<?php

class Usuario extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'usuarios';
    protected $fillable = array(
        'first_name',
        'last_name',
        'facebook_id',
        'email',
        'facebook_id',
        'facebook_access_token',
        'payment_transaction_id',
        'access_expiration_date',
        'welcome_email_sent');
}