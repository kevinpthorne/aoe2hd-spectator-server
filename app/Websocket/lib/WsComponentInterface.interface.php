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

    function process($client, $message, WsServer $server);

    function connected($client, WsServer $server);

    function closed($client, WsServer $server);

}