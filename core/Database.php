<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $config = config('database');
        $driver = self::driver();
        $dsn = $driver === 'pgsql'
            ? sprintf(
                'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
                $config['host'],
                $config['port'],
                $config['name'],
                $config['sslmode'] ?? 'prefer'
            )
            : sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['name'],
                $config['charset']
            );

        try {
            self::$connection = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            http_response_code(500);
            exit('Erro ao conectar ao banco de dados.');
        }

        return self::$connection;
    }

    public static function driver(): string
    {
        return strtolower((string) (config('database.driver') ?: 'mysql'));
    }
}
