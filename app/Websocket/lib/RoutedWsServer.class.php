<?php

namespace WsLib;

/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/14/2017
 * Time: 1:49 AM
 */
class RoutedWsServer extends WsServer
{
    private $routes = array();
    private $connections = array();

    function process($client, $message)
    {
        $handler = $this->connections[$client];
        if ($handler != false) {
            $handler->process($client, $message);
        }
    }

    function connected($client)
    {
        $handlerClass = $this->lookupHandlerByClient($client);
        $handler = new $handlerClass($this);
        if ($handler != false) {
            $handler->connected($client);
            $connections[$client] = $handler;
        }
    }

    function closed($client)
    {
        $handler = $this->connections[$client];
        if ($handler != false) {
            $handler->closed($client);
        }
    }

    /**
     * @param string $path
     * @param string $handler
     */
    public function addRoute($path, $handler)
    {
        if($path[0] !== "/") {
            $path = "/$path";
        }
        $this->stdout("Registered $path");
        $this->routes[$path] = $handler;
    }

    /**
     * @param $client
     * @return mixed
     */
    private function lookupHandlerByClient($client)
    {
        return $this->routes[$client->requestedResource];
    }

}