<?php

namespace WsLib;

/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/14/2017
 * Time: 1:44 PM
 */
interface WsComponentInterface
{

    function process(Client $client, $message, WsServer $server);

    function connected(Client $client, WsServer $server);

    function closed(Client $client, WsServer $server);

}