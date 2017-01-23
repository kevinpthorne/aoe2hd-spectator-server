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

    function process($client, $message);

    function connected($client);

    function closed($client);

}