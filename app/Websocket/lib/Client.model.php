<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/14/2017
 * Time: 1:20 AM
 */
namespace WsLib;

class Client
{
    public $socket;
    public $id;

    public $headers = array();

    public $handshake = false;

    public $handlingPartialPacket = false;
    public $partialBuffer = "";

    public $sendingContinuous = false;
    public $partialMessage = "";

    public $hasSentClose = false;

    function __construct($id, $socket) {
        $this->id = $id;
        $this->socket = $socket;
    }
}