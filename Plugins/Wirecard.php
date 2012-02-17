<?php

namespace OS\PaymentBundle\Plugins;

use Exception;

/**
 * @author ouardisoft
 */
class Wirecard
{

    /**
     *
     * @var string 
     */
    private $host = 'c3-test.wirecard.com';

    /**
     *
     * @var string 
     */
    private $path = '/secure/ssl-gateway';

    /**
     *
     * @var integer 
     */
    private $port = 443;

    /**
     *
     * @var string 
     */
    private $url;

    /**
     *
     * @var array 
     */
    private $headers;

    /**
     *
     * @var mixed 
     */
    private $results;

    /**
     *
     * @var mixed 
     */
    private $errors;

    /**
     *
     * @var string 
     */
    private $login = '56500';

    /**
     *
     * @var string 
     */
    private $password = 'TestXAPTER';

    /**
     *
     * @var string 
     */
    private $businessCaseSignature = '56500';

    /**
     *
     * @var array 
     */
    private $parameters;

    /**
     *
     * @var string 
     */
    private $xmlSchema;
    
    /**
     *
     * @return string 
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     *
     * @param string $uri 
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     *
     * @param string $path 
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     *
     * @return int 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     *
     * @param int $port 
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     *
     * @return string 
     */
    public function getUrl()
    {
        if (!$this->url) {
            $this->setUrl(sprintf('%s%s%s', 'https://', $this->getHost(), $this->getPath()));
        }

        return $this->url;
    }

    /**
     *
     * @param string $url 
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    /**
     *
     * @return array 
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     *
     * @param array $headers 
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     *
     * @return mixed 
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     *
     * @param mixed $results 
     */
    public function setResults($results)
    {
        $this->results = $results;
    }

    /**
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     *
     * @param string $login 
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @param string $password 
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     *
     * @return string 
     */
    public function getBusinessCaseSignature()
    {
        return $this->businessCaseSignature;
    }

    /**
     *
     * @param string $businessCaseSignature 
     */
    public function setBusinessCaseSignature($businessCaseSignature)
    {
        $this->businessCaseSignature = $businessCaseSignature;
    }

    /**
     *
     * @return array 
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     *
     * @param array $parameters 
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }    

    /**
     *
     * @return string 
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     *
     * @param string $host 
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     *
     * @return type 
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param type $errors 
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * 
     */
    public function execute($parameters = array())
    {
        $this->setParameters($parameters);

        $this->dispatch()->call();

        return $this;
    }

    /**
     * 
     */
    public function getUserPassword()
    {
        return sprintf('%s:%s', $this->getLogin(), $this->getPassword());
    }

    /**
     * 
     */
    public function dispatch()
    {
        // headers
        $headers = array();
        $headers[0] = 'Business Case Signature : ' . $this->getBusinessCaseSignature();
        $headers[1] = 'Content-Type : text/xml';

        $this->setHeaders($headers);

        // mandatory params
        $mandatoryParams = array('Login', 'Password', 'BusinessCaseSignature');
        foreach ($mandatoryParams as $param) {
            if (!array_key_exists($param, $this->parameters)) {
                throw new Exception(sprintf('You must define "%s" parameter.', $param));
            }

            call_user_func(array($this, 'set'. $param), $this->parameters[$param]);
        }

        // generate params
        $params = array();
        foreach ($this->parameters as $param => $value) {
            $params['{{ ' . $param . ' }}'] = $value;
        }
        $this->setParameters(array_merge($this->getDefaultParameters(), $params));

        // generate xml schema
        $this->generateXmlSchema();

        return $this;
    }

    /**
     * 
     */
    public function call()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, __DIR__.'/../tcclass3-2011.pem');
        curl_setopt($ch, CURLOPT_USERAGENT, 'WireCard Payment Request');
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->getUserPassword());
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getXmlSchema());

        $this->results = curl_exec($ch);
        $this->errors = curl_error($ch);

        curl_close($ch);
    }

    /**
     *
     * @return type 
     */
    public function getXmlSchema()
    {
        return $this->xmlSchema;
    }

    /**
     *
     * @param type $xmlSchema 
     */
    public function setXmlSchema($xmlSchema)
    {
        $this->xmlSchema = $xmlSchema;
    }

    /**
     *
     * @return string 
     */
    public function generateXmlSchema()
    {
        $xmlStr = "<?xml version = '1.0' encoding = 'UTF-8' ?>
<WIRECARD_BXML xmlns:xsi='http://www.w3.org/1999/XMLSchema-instance'
            xsi:noNamespaceSchemaLocation='wirecard.xsd'>
    <W_REQUEST>
        <W_JOB>
            <JobID>{{ JobID }}</JobID>
            <BusinessCaseSignature>{{ BusinessCaseSignature }}</BusinessCaseSignature>
            <FNC_CC_TRANSACTION mode='{{ Mode }}'>
                <FunctionID>{{ FunctionID }}</FunctionID>
                <CC_TRANSACTION>
                    <TransactionID>{{ TransactionID }}</TransactionID>
                    <Amount>{{ Amount }}</Amount>
                    <Currency>{{ Currency }}</Currency>
                    <CountryCode>{{ CountryCode }}</CountryCode>
                    <RECURRING_TRANSACTION>
                        <Type>Single</Type>
                    </RECURRING_TRANSACTION>
                    <CREDIT_CARD_DATA>
                        <CreditCardNumber>{{ CreditCardNumber }}</CreditCardNumber>
                        <CVC2>{{ CVC2 }}</CVC2>
                        <ExpirationYear>{{ ExpirationYear }}</ExpirationYear>
                        <ExpirationMonth>{{ ExpirationMonth }}</ExpirationMonth>
                        <CardHolderName>{{ CardHolderName }}</CardHolderName>
                    </CREDIT_CARD_DATA>
                    <CONTACT_DATA>
                        <IPAddress>{{ IPAddress }}</IPAddress>
                    </CONTACT_DATA>
                    <CORPTRUSTCENTER_DATA>
                        <ADDRESS>
                            <Address1>{{ Address1 }}</Address1>
                            <City>{{ City }}</City>
                            <ZipCode>{{ ZipCode }}</ZipCode>
                            <State>{{ State }}</State>
                            <Country>{{ Country }}</Country>
                            <Phone>{{ Phone }}</Phone>
                            <Email>{{ Email }}</Email>
                        </ADDRESS>
                    </CORPTRUSTCENTER_DATA>
                </CC_TRANSACTION>
            </FNC_CC_TRANSACTION>
        </W_JOB>
    </W_REQUEST>
</WIRECARD_BXML>";

        $this->xmlSchema = str_replace(array_keys($this->getParameters()), array_values($this->getParameters()), $xmlStr);
    }

    public function getDefaultParameters()
    {
        $params = array(
            '{{ JobID }}'                 => null,
            '{{ BusinessCaseSignature }}' => null,
            '{{ Mode }}'                  => null,
            '{{ FunctionID }}'            => null,
            '{{ TransactionID }}'         => null,
            '{{ Amount }}'                => null,
            '{{ Currency }}'              => null,
            '{{ CountryCode }}'           => null,
            '{{ CreditCardNumber }}'      => null,
            '{{ CVC2 }}'                  => null,
            '{{ CardHolderName }}'        => null,
            '{{ ExpirationMonth }}'       => null,
            '{{ ExpirationYear }}'        => null,
            '{{ Email }}'                 => null,
            '{{ Phone }}'                 => null,
            '{{ Country }}'               => null,
            '{{ State }}'                 => null,
            '{{ ZipCode }}'               => null,
            '{{ City }}'                  => null,
            '{{ Address1 }}'              => null,
            '{{ IPAddress }}'             => null,
        );   

        return $params;
    }
}