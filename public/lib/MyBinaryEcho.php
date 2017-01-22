<?php
/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/14/2017
 * Time: 12:16 AM
 */
namespace AoE2HDSpectatorServer;

use Ratchet\ConnectionInterface;

class MyBinaryEcho implements \Ratchet\WebSocket\MessageComponentInterface {
    public function onMessage(ConnectionInterface $from, \Ratchet\RFC6455\Messaging\MessageInterface $msg) {
        $from->send($msg);
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}