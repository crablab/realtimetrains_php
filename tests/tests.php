<?php

/*
Disclaimer: this is not how to do TDD and doesn't use any test framework. It essentially tests (live) that the SDK does vaguely the right things. 
*/

include("../src/realtime.php");

$rtt = new realtime\realtime("", "");

$rtt->locationList("PBO");

//$crs, $type=null, $to=null, $from=null, $timestamp=null, $rows=5

$rtt->arrivalsBoard("PBO", "GLGC", NULL);

$rtt->departuresBoard("PBO", "KGX", NULL);
?>