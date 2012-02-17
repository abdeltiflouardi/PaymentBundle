How to use:

    $payment = $this->get('payment.factory');
    $payment->execute(array(
        'plugin'  => 'Saferpay',
        'options' => array(
            'spPassword' => 'XAjc3Kna',
            'ACCOUNTID'  => '99867-94913159',
            'ORDERID'    => '123456789-001',
            'AMOUNT'     => '4000',
            'CURRENCY'   => 'EUR',
            'PAN'        => '9451123100000004',
            'EXP'        => '1214',
            'CVC'        => '123'
            )));
    $payment->getResults();