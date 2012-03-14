How to use:

Check result

        // check request
        $payment = $this->get('payment.factory');
        $r = $payment->setPlugin('Paypal')
                ->setOptions(array('env' => 'sandbox'))
                ->validate()
                ->getResultCURL();

        var_dump($r);


Redirect to paypal

        /**
         * Send request 
         */
        $payment = $this->get('payment.factory');
        $payment->execute(array(
            'plugin'  => 'Paypal',
            'options' => array(
                'env'           => 'sandbox',
                'cmd'           => '_xclick',
                'business'      => YOUR_BUSINESS,
                'currency_code' => 'EUR',
                'item_name'     => 'Chemise',
                'amount'        => 1000,
                'shipping'      => 600,
                'tax'           => 200,
                'invoice'       => 40009,
                'custom'        => 7856,
                'no_note'       => '1',
                'notify_url'    => '',
                'return'        => '',
                'cancel_return' => '',
                'bn'            => ''
                )));

        $r = $payment->redirect();

        return $r;