<?php

namespace Amqp\Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;

class AmqpServiceProvider implements ServiceProviderInterface
{
    const AMQP = 'amqp';
    const AMQP_CONNECTIONS = 'amqp.connections';
    const AMQP_FACTORY = 'amqp.factory';

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Container $app)
    {
        $app[self::AMQP_CONNECTIONS] = array(
            'default' => array(
                'host' => 'localhost',
                'port' => 5672,
                'username' => 'guest',
                'password' => 'guest',
                'vhost' => '/'
            )
        );

        $app[self::AMQP_FACTORY] = $app->protect(function ($host = 'localhost', $port = 5672, $username = 'guest', $password = 'guest', $vhost = '/') use ($app) {
            return $app[self::AMQP]->createConnection($host, $port, $username, $password, $vhost);
        });

        $app[self::AMQP] = function () use ($app) {
            return new AmqpConnectionProvider($app[self::AMQP_CONNECTIONS]);
        };
    }
}
