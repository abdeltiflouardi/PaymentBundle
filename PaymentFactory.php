<?php

namespace OS\PaymentBundle;

use Exception;

/**
 * @author ouardisoft
 */
class PaymentFactory
{

    private $plugin;
    private $container;

    function __construct($container)
    {
        $this->container = $container;
    }

    public function __call($name, $arguments)
    {
        return $this->getPlugin()->$name($arguments);
    }

    public function getPlugin($plugin = null)
    {
        if ($plugin) {
            $this->setPlugin($plugin);
        }

        if (method_exists($this->plugin, 'setContainer')) {
            $this->plugin->setContainer($this->container);
        }

        return $this->plugin;
    }

    public function setPlugin($plugin)
    {
        $exists = true;
        if (!class_exists($plugin)) {
            $exists = false;
        }

        if (!class_exists(sprintf('OS\\PaymentBundle\\Plugins\\%s', $plugin)) && !$exists) {
            $exists = false;
        } else {
            $plugin = sprintf('OS\\PaymentBundle\\Plugins\\%s', $plugin);
            $exists = true;
        }

        if ($exists == false) {
            throw new Exception(sprintf('Plugin %s not found.', $plugin));
        }

        $this->plugin = new $plugin;

        return $this;
    }

    public function execute($args = array())
    {
        $this->setPlugin($args['plugin']);

        $this
                ->getPlugin()
                ->execute($args['options']);

        return $this;
    }

}