<?php
class DownStreamer
{
    private $_fileName;
    private $_directory;

    public function __construct($directory, $fileName)
    {
        $this->_directory = $directory;
        $this->_fileName = urldecode($fileName);
    }
    public function send()
    {
        if (!file_exists($this->_directory . $this->_fileName)) {
            header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
            die(404);
        }
        $downStream = fopen('php://output', "w+");
        $fileReader = fopen($this->_directory . $this->_fileName, "r");
        while(true) {
            $buffer = fgets($fileReader, 64);
            if (strlen($buffer) == 0) {
                fclose($fileReader);
                fclose($downStream);
                return true;
            }
            fwrite($downStream, $buffer);
        }
        return false;
    }
}