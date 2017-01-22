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

    function process($client, $message, WsServer $server)
    {
        $handler = $this->lookupHandlerByClient($client);
        if ($handler != false) {
            $handler->process($client, $message, $server);
        }
    }

    function connected($client, WsServer $server)
    {
        $handler = $this->lookupHandlerByClient($client);
        if ($handler != false) {
            $handler->connected($client, $server);
        }
    }

    function closed($client, WsServer $server)
    {
        $handler = $this->lookupHandlerByClient($client);
        if ($handler != false) {
            $handler->closed($client, $server);
        }
    }

    public function addRoute(string $path, WsComponentInterface $handler)
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