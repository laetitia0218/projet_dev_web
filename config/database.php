<?php

const DB_HOST = 'localhost';
const DB_NAME = '2026_M1';
const DB_USER = 'root';
const DB_PASS = '';   

/**
 * @return PDO
 */
function getPDO(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
        ]);
    }
    return $pdo;
}