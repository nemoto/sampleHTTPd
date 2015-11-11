<?php
namespace SampleHttpd;

// CONFIGURATION
$conf = array(
    'LISTEN_PORT' => 9999,
    'MAX_REQUEST' => 4096,
);

// CREATE A SOCKET
$port = $conf['LISTEN_PORT'];
$sock = socket_create_listen($port);

// LISTEN LOOP
while (true) {

    // ACCEPT (= Start Listen)
    $clientsock = socket_accept($sock);
    if ($clientsock === false) die("Fail to create socket\n");
    // FOR ONE SESSION (other client will be waiting)
    $data = null;
    while (true && strlen($data) < $conf['MAX_REQUEST']) {
        $buf = socket_read($clientsock, 1024);
        $data .= $buf;
        if ($buf == "") {
            break;
        }
    }
    // CLOSE SOCKET
    socket_close($clientsock);
    // ECHO current session Data
    echo $data;
}

exit;
