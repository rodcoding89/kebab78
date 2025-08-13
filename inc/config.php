<?php
    // with this code line, we create a database object. With the object we can user some sql request like insert,select,update or delete
    $env = "dev";

    $host    = $env == 'dev' ? 'localhost' : 'sql204.infinityfree.com';
    $db      = $env == 'dev' ? 'restokebab'  : 'if0_39696057_kebab78';
    $user    = $env == 'dev' ? 'root' : 'if0_39696057';
    $pass    = $env == 'dev' ? '' : '73FeNS4gdPD';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
?>