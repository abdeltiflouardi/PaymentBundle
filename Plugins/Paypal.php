<?php

namespace OS\PaymentBundle\Plugins;

use Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\DependencyInjection\Container;

/**
 * @author ouardisoft
 */
class Paypal
{

    CONST URI_PAYPAL_SANDBOX = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    CONST URI_PAYPAL_DEFAULT = 'https://www.paypal.com/cgi-bin/webscr';

    private $uri;
    private $url;
    private $options;
    private $redirect;
    private $env = 'default';
    private $results;

    /**
     *
     * @var Container 
     */
    private $container;
    private $resultCURL;

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
    public function getEnv()
    {
        return $this->env;
    }

    /**
     *
     * @param type $env 
     */
    public function setEnv($env)
    {
        $this->env = $env;
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
        if (array_key_exists('env', $options)) {
            $this->setEnv($options['env']);

            unset($options['env']);
        }

        switch ($this->getEnv()) {
            case 'default': $this->setUri(static::URI_PAYPAL_DEFAULT);
                break;
            case 'sandbox': $this->setUri(static::URI_PAYPAL_SANDBOX);
                break;
        }

        $this->options = $options;

        return $this;
    }

    /**
     *
     * @return type 
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     *
     * @param type $redirect 
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
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
    public function execute($options)
    {
        $this->setOptions($options);

        $this->call();

        return $this;
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
    public function call()
    {
        $url = sprintf('%s?%s', $this->getUri(), $this->getQueryStringFromOptions());

        $response = new RedirectResponse($url);

        $this->setRedirect($response);
    }

    /**
     * 
     */
    public function getTestOptions()
    {
        return array();
    }

    public function validate()
    {
        $request = $this->getContainer()->get('request')->request;

        $request->set('cmd', '_notify-validate');
        $parameters = http_build_query($request->all());

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->getUri());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $this->resultCURL = curl_exec($ch);

        curl_close($ch);

        return $this;
    }

    /**
     *
     * @return Container 
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     *
     * @param Container $container 
     */
    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     *
     * @return type 
     */
    public function getResultCURL()
    {
        return $this->resultCURL;
    }

    /**
     *
     * @param type $resultCURL 
     */
    public function setResultCURL($resultCURL)
    {
        $this->resultCURL = $resultCURL;
    }

}