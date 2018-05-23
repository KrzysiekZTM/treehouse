<?php
/**
 * Created by PhpStorm.
 * User: Krzysztof Jabłoński
 * Date: 05.04.2018
 * Time: 07:21
 */

try {
    $host = "localhost";
    $dbName = "database";
    $dsn = "mysql:host=".$host.";dbname=".$dbName;
    $username = 'root';
    $password = '';
    $options = array();
    $db = new PDO($dsn, $username, $password, $options);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "Unable to connect to database";
    echo $e->getMessage();
    exit;
}