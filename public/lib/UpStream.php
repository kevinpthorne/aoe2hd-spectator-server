<?php
namespace AoE2HDSpectatorServer;

/**
 * Created by PhpStorm.
 * User: kevint
 * Date: 1/12/2017
 * Time: 12:47 PM
 */
class UpStream implements \Ratchet\MessageComponentInterface
{

    protected $streamers;

    /**
     * UpStream constructor.
     */
    public function __construct()
    {
        $this->streamers = new \SplObjectStorage();
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(\Ratchet\ConnectionInterface $conn)
    {
        echo "New connection! ({$conn->resourceId})\n";
        $this->streamers->attach($conn);
        $conn->sizeSent = 0;
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  \Ratchet\ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(\Ratchet\ConnectionInterface $conn)
    {
        echo "Connection {$conn->resourceId} has disconnected, received {$conn->sizeSent}kb of data \n";
        $this->streamers->detach($conn);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  \Ratchet\ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(\Ratchet\ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(\Ratchet\ConnectionInterface $streamer, $data)
    {
        $query = Util::getQueryParameters($streamer);
        //print_r("Receiving {$query['filename']} from " . $query['player'] . "\n");
        $filename = str_replace(".aoe2record", "", $query['filename']);
        $player = $query['player'];
        // @todo: see if file exists. if so, deny
        $fileWriter = fopen("recs/" . $player . "." . $filename . '.aoe2record', "a");
        $success = fwrite($fileWriter, $data);
        $streamer->sizeSent += strlen(serialize($data))/1024;
        if ($success) {
            $msg = 'continue';
        } else {
            $msg = 'error';
        }
        fclose($fileWriter);
        $streamer->send($msg);
    }


}