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

    private $_server;

    /**
     * EchoComponent constructor.
     * @param $_server
     */
    public function __construct($server)
    {
        $this->_server = $server;
    }


    function process($client, $message)
    {
        $this->_server->send($client, $message);
    }

    function connected($client)
    {
        echo "---ECHO!!\n";
    }

    function closed($client)
    {
        // TODO: Implement closed() method.
    }
}