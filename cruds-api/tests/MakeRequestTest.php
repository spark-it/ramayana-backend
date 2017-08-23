<?php

namespace BrPayments;

use \BrPayments\Payments\PagSeguro;

include __DIR__ . '/../src/Payments/PagSeguro.php';
include __DIR__ . '/../src/Requests/PagSeguro.php';
include __DIR__ . '/../src/MakeRequest.php';

class MakeRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testPagSeguroRequest()
    {
        $access = [
            'email' => 'professorramayana@gmail.com',
            'token' => '6C461B67BD034EE8A9B1BA64E40F744D',
            'currency' => 'BRL',
            'reference' => 'REF1234'
        ];

        $pag_seguro = new Payments\PagSeguro($access);

        //name, areacode, phone, email
        $pag_seguro->customer('José Comprador', 11, 99999999, 'c30724139981788620484@sandbox.pagseguro.com.br');
        $pag_seguro->shipping(
            1,
            'Av. PagSeguro',
            99,
            '99o andar',
            'Jardim Internet',
            99999999,
            'Cidade Exemplo',
            'SP',
            'ATA'
        );

        //id, description, amount, quantity, wheight
        $pag_seguro->addProduct(1, 'Curso de PHP', 19.99, 20);
        $pag_seguro->addProduct(2, 'Livro de Laravel', 15, 31, 1500);


        //Request
        $pag_seguro_request = new Requests\PagSeguro;

        $response = (new MakeRequest())->post($pag_seguro, true);

        $xml = new \SimpleXMLElement((string)$response);

        $url = $pag_seguro_request->getUrlFinal($xml->code, true);

        $this->assertTrue(is_string($url));


    }
}