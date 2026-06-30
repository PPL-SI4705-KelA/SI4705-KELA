<?php

$host = 'ep-sweet-bonus-anb5fldu-pooler.c-6.us-east-1.aws.neon.tech';
$port = 5432;
$dbname = 'Greenovete';
$user = 'neondb_owner';
$passwords = [
    "endpoint=ep-sweet-bonus-anb5fldu\$npg_PvF7HKSzXl4M",
    "endpoint=ep-sweet-bonus-anb5fldu;npg_PvF7HKSzXl4M",
    "npg_PvF7HKSzXl4M"
];

foreach ($passwords as $pass) {
    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
        $pdo = new PDO($dsn, $user, $pass);
        echo "SUCCESS with password: " . $pass . "\n";
    } catch (PDOException $e) {
        echo "FAILED with password: " . $pass . " - Error: " . $e->getMessage() . "\n";
    }
}
