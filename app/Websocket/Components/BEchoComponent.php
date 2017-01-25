<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 12:45 AM
 */

namespace AoE2HDSpectatorServer;

use WsLib\Client;
use WsLib\WsComponentInterface;
use WsLib\WsServer;

class BEchoComponent implements WsComponentInterface
{

    function process(Client $client, $message, WsServer $server)
    {
        $server->send($client, $message, 'binary');
    }

    function connected(Client $client, WsServer $server)
    {
        echo "BECHO!!\n";
    }

    function closed(Client $client, WsServer $server)
    {
        // TODO: Implement closed() method.
    }

}