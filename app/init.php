<?php
$redisHost = 'redis';   // Docker service name
$redisPort = 6379;

// Connect manually if you need
$redis = new Redis();
$redis->connect($redisHost, $redisPort);

// Set Redis as session handler
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', "tcp://$redisHost:$redisPort");

// Start session
session_start();
?>
