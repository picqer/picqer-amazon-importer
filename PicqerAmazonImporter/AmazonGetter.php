<?php
namespace PicqerAmazonImporter;

class AmazonGetter {

    protected $config;
    protected $data;

    public function __construct($config, $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    public function getNewOrders()
    {
        $orders = array();

        foreach ($this->config['amazon-stores'] as $amazonStoreId => $amazonStoreData) {
            $amazon = new \AmazonOrderList('AmazonStore' . $amazonStoreId, false, null, __DIR__ . '/../config-amazon.php'); // store name matches the array key in the config file
            $amazon->setLimits('Modified', '- 100 hours'); // accepts either specific timestamps or relative times
            $amazon->setFulfillmentChannelFilter('MFN'); // no Amazon-fulfilled orders
            $amazon->setOrderStatusFilter(array('Unshipped', 'PartiallyShipped')); // only orders that needs shipping
            $amazon->setUseToken(); // tells the object to automatically use tokens right away
            $amazon->fetchOrders(); // this is what actually sends the request
            $amazonorders = $amazon->getList();

            /* @var \AmazonOrder $amazonorder */
            foreach ($amazonorders as $amazonorder) {
                if (!in_array($amazonorder->getAmazonOrderId(), $this->data['processedOrders'])) {
                    $order = array();
                    $order['amazonOrderId'] = $amazonorder->getAmazonOrderId();
                    $order['purchaseDate'] = $amazonorder->getPurchaseDate();
                    $order['name'] = $amazonorder->getBuyerName();
                    $order['email'] = $amazonorder->getBuyerEmail();
                    $order['status'] = $amazonorder->getOrderStatus();
                    $order['address'] = $amazonorder->getShippingAddress();
                    $order['orderitems'] = array();

                    /* @var \AmazonOrderItemList $items */
                    $items = $amazonorder->fetchItems();
                    foreach ($items as $item) {
                        $order['orderitems'][] = array(
                            'productcode' => $item['SellerSKU'],
                            'name'        => $item['Title'],
                            'amount'      => $item['QuantityOrdered'],
                            'price'       => $item['ItemPrice']['Amount']
                        );
                    }

                    $orders[] = $order;
                }
            }
        }

        return $orders;
    }
}