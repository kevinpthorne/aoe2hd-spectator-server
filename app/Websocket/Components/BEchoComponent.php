<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/15/2017
 * Time: 12:45 AM
 */

namespace AoE2HDSpectatorServer;

use WsLib\WsComponentInterface;
use WsLib\WsServer;

class BEchoComponent implements WsComponentInterface
{

    function process($client, $message, WsServer $server)
    {
        $server->send($client, $message, 'binary');
    }

    function connected($client, WsServer $server)
    {
        echo "BECHO!!\n";
    }

    function closed($client, WsServer $server)
    {
        // TODO: Implement closed() method.
    }

}