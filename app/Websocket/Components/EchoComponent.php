<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 12:44 AM
 */

namespace AoE2HDSpectatorServer;

use WsLib\WsComponentInterface;
use WsLib\WsServer;

class EchoComponent implements WsComponentInterface
{
    function process($client, $message, WsServer $server)
    {
        $server->send($client, $message);
    }

    function connected($client, WsServer $server)
    {
        echo "---ECHO!!\n";
    }

    function closed($client, WsServer $server)
    {
        // TODO: Implement closed() method.
    }
}