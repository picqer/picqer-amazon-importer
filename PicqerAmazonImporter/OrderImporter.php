<?php
namespace PicqerAmazonImporter;

class OrderImporter {

    protected $picqerclient;
    protected $config;

    public function __construct($picqerclient, $config)
    {
        $this->picqerclient = $picqerclient;
        $this->config = $config;
    }

    public function importOrders($orders)
    {
        $orderids = array();
        foreach ($orders as $order) {
            $orderid = $this->createOrder($order);
            if (! is_null($orderid)) {
                $orderids[$order['amazonOrderId']] = $orderid;
            }
        }

        return $orderids;
    }

    public function createOrder($amazonorder)
    {
        $order = array(
            'idcustomer' => $this->config['picqer-idcustomer'],
            'reference' => $amazonorder['amazonOrderId'],
            'deliveryname' => $amazonorder['address']['Name'],
            'deliveryaddress' => $amazonorder['address']['AddressLine1'],
            'deliveryaddress2' => $amazonorder['address']['AddressLine2'],
            'deliveryzipcode' => $amazonorder['address']['PostalCode'],
            'deliverycity' => $amazonorder['address']['City'],
            'deliverycountry' => $amazonorder['address']['CountryCode'],
            'products' => array()
        );

        if (empty($amazonorder['address']['AddressLine1']) && ! empty($amazonorder['address']['AddressLine2'])) {
            $order['deliveryaddress'] = $amazonorder['address']['AddressLine2'];
            $order['deliveryaddress2'] = $amazonorder['address']['AddressLine1'];
        }

        foreach ($amazonorder['orderitems'] as $orderitem) {
            $productcode = $this->getIdproductFromProductcode($orderitem['productcode']);

            if ($productcode === false) {
                return null;
            }

            $order['products'][] = array(
                'idproduct' => $productcode,
                'amount' => $orderitem['amount'],
                'price' => $orderitem['price']
            );
        }

        $result = $this->picqerclient->addOrder($order);
        if (isset($result['data']['idorder'])) {
            logThis('Order ' . $amazonorder['amazonOrderId'] . ' / ' . $result['data']['idorder'] . ' added to Picqer');
            if ($this->config['picqer-close-orders']) {
                $this->picqerclient->closeOrder($result['data']['idorder']);
            }
            return $result['data']['orderid'];
        } else {
            logThis('ERROR: Could not create order in Picqer');
            throw new \Exception('Could not create order in Picqer');
        }
    }

    public function getIdproductFromProductcode($productcode)
    {
        $productresult = $this->picqerclient->getProductByProductcode($productcode);
        if (isset($productresult['data'])) {
            return $productresult['data']['idproduct'];
        } else {
            logThis('Cannot find product ' . $productcode);
            return false;
        }
    }

}
