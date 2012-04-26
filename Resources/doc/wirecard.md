Parameters:

        $payment = $this->get('payment.factory');
        $payment->execute(array(
            'plugin'  => 'Wirecard',
            'options' => array(
                'Login'                 => null,
                'Password'              => null,
                'BusinessCaseSignature' => null,
                'JobID'                 => null,
                'Mode'                  => null,
                'FunctionID'            => null,
                'TransactionID'         => null,
                'Amount'                => null,
                'Currency'              => null,
                'CountryCode'           => null,
                'CreditCardNumber'      => null,
                'CVC2'                  => null,
                'CardHolderName'        => null,
                'ExpirationMonth'       => null,
                'ExpirationYear'        => null,
                'Email'                 => null,
                'Phone'                 => null,
                'Country'               => null,
                'State'                 => null,
                'ZipCode'               => null,
                'City'                  => null,
                'Address1'              => null,
                'IPAddress'             => null,
            )
        ));

Example        

        $payment = $this->get('payment.factory');
        $payment->execute(array(
            'plugin'  => 'Wirecard',
            'options' => array(
                'Login'                 => 56500,
                'Password'              => 'TestXAPTER',
                'BusinessCaseSignature' => '56500',
                'JobID'                 => 'job 2',
                'Mode'                  => 'demo',
                'FunctionID'            => 'WireCard Test',
                'TransactionID'         => 2,
                'Amount'                => 1000,
                'Currency'              => 'USD',
                'CountryCode'           => 'US',
                'CreditCardNumber'      => '4200000000000000',
                'CVC2'                  => '000',
                'CardHolderName'        => 'Wirecard Test',
                'ExpirationMonth'       => '01',
                'ExpirationYear'        => '2014',
                'Email'                 => 'support@wirecard.com',
                'IPAddress'             => '127.0.0.1',
            )
        ));
        $results = $payment->getResults();
        var_dump($results);


Authorization example:

        $options = array(
                    'Login'                 => 56500,
                    'Password'              => 'TestXAPTER',
                    'BusinessCaseSignature' => '56500',
                    'JobID'                 => 'job 2',
                    'Mode'                  => 'demo',
                    'FunctionID'            => 'WireCard Test',
                    'TransactionID'         => 2,
                    'ActionType'            => 'Authorization',
                    'Amount'                => 1000,
                    'Currency'              => 'USD',
                    'CountryCode'           => 'US',
                    'CreditCardNumber'      => '4200000000000000',
                    'CVC2'                  => '000',
                    'CardHolderName'        => 'Wirecard Test',
                    'ExpirationMonth'       => '01',
                    'ExpirationYear'        => '2014',
                    'Email'                 => 'support@wirecard.com',
                    'IPAddress'             => '127.0.0.1',            
                );

        $p = $this->get('payment.factory');

        $exec = $p->execute(array('plugin' => 'Wirecard', 'options' => $options));
        $guwid = $exec->getGuWID();
        $results = $exec->getResults();

Purchase Repeated

        $options = array(
                    'Login'                 => 56500,
                    'Password'              => 'TestXAPTER',
                    'BusinessCaseSignature' => '56500',
                    'JobID'                 => 'job 2',
                    'Mode'                  => 'demo',
                    'FunctionID'            => 'WireCard Test',
                    'TransactionID'         => 2,
                    'ActionType'            => 'PurchaseRepeated',
                    'GuWID'		    => $guwid,
                );

        $p = $this->get('payment.factory');

        $exec = $p->execute(array('plugin' => 'Wirecard', 'options' => $options));
        $r = $exec->getResults();

        var_dump($r, $exec->getGuWID());
        exit;