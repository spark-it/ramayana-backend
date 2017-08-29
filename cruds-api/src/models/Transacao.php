<?php
/**
 * Created by PhpStorm.
 * User: mayckxavier
 * Date: 28/08/17
 * Time: 15:47
 */

namespace src\models;


class Transacao extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'transactions';
    protected $fillable = array(
        'ref',
        'product',
        'value',
        'usuarios_id');
}