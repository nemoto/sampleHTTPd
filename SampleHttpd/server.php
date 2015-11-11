<?php
namespace SampleHttpd;

// CONFIGURATION
$conf = array(
    'listen_port' => 9999,
);

// CREATE A SOCKET
$port = $conf['listen_port'];
$sock = socket_create_listen($port);

// ACCEPT (= Start Listen)
$clientsock = socket_accept($sock);
// LISTEN LOOP
$data = null;
while (true) {
    $buf = socket_read($clientsock, 1024);
    $data .= $buf;
    if ($buf == "") {
        break;
    }
}

// CLOSE SOCKET
socket_close($clientsock);

// ECHO Data
echo $data;
exit;
