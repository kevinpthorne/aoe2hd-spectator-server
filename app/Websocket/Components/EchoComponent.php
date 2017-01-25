<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 12:44 AM
 */

namespace AoE2HDSpectatorServer;

use WsLib\Client;
use WsLib\WsComponentInterface;
use WsLib\WsServer;

class EchoComponent implements WsComponentInterface
{

    function process(Client $client, $message, WsServer $server)
    {
        $server->send($client, $message);
    }

    function connected(Client $client, WsServer $server)
    {
        echo "---ECHO!!\n";
    }

    function closed(Client $client, WsServer $server)
    {
        // TODO: Implement closed() method.
    }
}