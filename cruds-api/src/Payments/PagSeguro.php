<?php

namespace BrPayments\Payments;

class PagSeguro
{

    protected $config;
    protected $sender;
    protected $shipping;
    protected $products;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function customer($name, $areacode, $phone, $email)
    {
        $this->sender = [
            'senderName' => $name,
            'senderAreaCode' => $areacode,
            'senderPhone' => $phone,
            'senderEmail' => $email
        ];
    }

    public function shipping($type, $street, $number, $complement, $district, $postal_code, $city, $state, $country)
    {
        $this->shipping = [
            'shippingType' => $type,
            'shippingAddressStreet' => $street,
            'shippingAddressNumber' => $number,
            'shippingAddressComplement' => $complement,
            'shippingAddressDistrict' => $district,
            'shippingAddressPostalCode' => $postal_code,
            'shippingAddressCity' => $city,
            'shippingAddressState' => $state,
            'shippingAddressCountry' => $country
        ];
    }

    public function addProduct($id, $description, $amount, $quantity, $weight = null)
    {
        $index = count($this->products);


        $this->products[$index] = [
            'id' => $id,
            'description' => $description,
            'amount' => $amount,
            'quantity' => $quantity
        ];

        if ($weight != null) {
            $this->products[$index]['weight'] = $weight;
        }
    }

    public function removeProduct($id){
        $products = array_filter($this->products, function($product) use($id){
            return $product['id'] != $id;
        });

        $this->products = array_values($products);
    }

    public function __toString()
    {
        return http_build_query($this->toArray());
    }

    public function toArray()
    {
        $items = [];
        foreach ($this->products as $k => $product) {
            $counter = $k + 1;
            $items['itemId' . $counter] = $product['id'];
            $items['itemDescription' . $counter] = $product['description'];
            $items['itemAmount' . $counter] = number_format($product['amount'], 2, '.', '');
            $items['itemQuantity' . $counter] = $product['quantity'];

            if (!empty($product['weight'])) {
                $items['itemWeight' . $counter] = $product['weight'];
            }
        }
        return array_merge($this->config, $items, $this->sender, $this->shipping);
    }
}