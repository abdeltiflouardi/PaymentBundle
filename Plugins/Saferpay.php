<?php

namespace OS\PaymentBundle\Plugins;

/**
 * @author ouardisoft
 */
class Saferpay
{

    private $uri = 'https://www.saferpay.com/hosting/execute.asp';
    private $url;
    private $options;
    private $results;

    /**
     *
     * @return type 
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     *
     * @param type $uri 
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     *
     * @return type 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * @param type $url 
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     *
     * @return type 
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     * @param type $options 
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     *
     * @return type 
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     *
     * @param type $results 
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

    /**
     * 
     */
    public function getQueryStringFromOptions()
    {
        return http_build_query($this->getOptions());
    }

    /**
     * 
     */
    public function execute($options)
    {
        $this->setOptions($options);

        $this->dispatch()->call();

        return $this;
    }

    /**
     * 
     */
    public function dispatch()
    {
        // Generate url
        $this->setUrl(sprintf('%s%s%s', $this->getUri(), '?', $this->getQueryStringFromOptions()));

        return $this;
    }

    /**
     * 
     */
    public function call()
    {
        $content = file_get_contents($this->getUrl());

        preg_match('/([^:]+):(.+)/i', $content, $matches);
        $status = $matches[1];

        if ($status == 'OK') {
            $sx = (array) simplexml_load_string($matches[2]);
            list($key, $attributes) = each($sx);

            $this->setResults($attributes);
        } else {
            $err['RESULT']      = $matches[1];
            $err['AUTHMESSAGE'] = $matches[2];

            $this->setResults($err);
        }
    }

    /**
     * 
     */
    public function getTestOptions()
    {
        return array(
            'spPassword' => 'XAjc3Kna',
            'ACCOUNTID'  => '99867-94913159',
            'ORDERID'    => '123456789-001',
            'AMOUNT'     => '4000',
            'CURRENCY'   => 'EUR',
            'PAN'        => '9451123100000004',
            'EXP'        => '1214',
            'CVC'        => '123'
        );
    }

}