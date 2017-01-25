<?php

namespace WsLib;

class RoutedWsServer extends WsServer
{
    private $routes = array();
    private $connections = array();

    function process(Client $client, $message, WsServer $server)
    {
        $handler = $this->connections[$client->id];
        if ($handler != false) {
            $handler->process($client, $message, $this);
        }
    }

    function connected(Client $client, WsServer $server)
    {
        $handlerClass = $this->lookupHandlerByClient($client);
        $handler = new $handlerClass();
        if ($handler != false) {
            $handler->connected($client, $this);
            $this->connections[$client->id] = $handler;
        }
    }

    function closed(Client $client, WsServer $server)
    {
        $handler = $this->connections[$client->id];
        if ($handler != false) {
            $handler->closed($client, $this);
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