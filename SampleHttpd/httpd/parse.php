<?php
namespace SampleHttpd\Httpd;

define("HTTP_METHODS", 'get,put,post,delete,head'); // HTTP/1.0


class RequrstParser
{

    private $raw_header = null;
    private $method     = array();
    private $header     = array();
    private $body       = null;
    private $data       = null;
    
    public function __construct($data)
    {
        $this->header = array();
        $this->body   = null;
        $this->data   = $data;
    }

    private function __destruct()
    {
        unset($this->header);
        unset($this->body);
    }

    public function parse()
    {
        $arr = explode("\n", $this->data);
        if (!is_array($arr)) $arr = array();
        
        $buf =& $this->raw_header;
        foreach($arr as $line) {
            if (strlen($line) === 1 &&
                ord(substr($line, 1)) === 0 &&
                $this->raw_header !== null) {
                $buf =& $this->body;
                continue;
            }
            $buf .= $line . "\n";
        }
        $this->parseHeader();
    }

    public function rawHeader()
    {
        return $this->raw_header;
    }

    public function method()
    {
        return $this->method;
    }

    public function headers()
    {
        return $this->header;
    }

    public function body()
    {
        return $this->body;
    }

    private function parseHeader()
    {
        $arr = explode("\n", $this->raw_header);
        if (!is_array($arr)) return;// ToDo: ErrorHandling.
        
        $this->parseMethod(array_shift($arr));
        $this->parseEachHeaders($arr);
    }

    private function parseMethod($line)
    {
        $arr = explode(" ", $line);
        $method = array_shift($arr);
        $path   = array_shift($arr);
        if (!in_array(strtolower($method), explode(",", HTTP_METHODS))) {
            return;  // ToDo: ErrorHandling.
        }
        $this->method = array("METHOD"  => strtoupper($method),
                              "PATH"    => $path,
                              "VERSION" => implode(" ", $arr));
        return;
    }
    

    private function parseEachHeaders(array $lines)
    {
        foreach($lines as $line) {
            $arr = explode(" ", $line);
            $this->header[array_shift($arr)] = implode(" ", $arr);
        }
    }
}
