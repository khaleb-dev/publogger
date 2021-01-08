<?php
/**
 * @link        https://publogger.khaleb.dev
 * @copyright   Copyright (c) 2021 Publogger
 * @license     MIT License    
 */

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => \Doctrine\DBAL\Driver\PDOMySql\Driver::class,
                'params' => [
                    'host'     => 'localhost',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'publogger',
                    'port'     => '3306',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'numeric_functions' => [
                    'HOUR' => 'DoctrineExtensions\Query\Mysql\Hour',
                    'DAY' => 'DoctrineExtensions\Query\Mysql\Day',
                    'MONTH' => 'DoctrineExtensions\Query\Mysql\Month',
                    'YEAR' => 'DoctrineExtensions\Query\Mysql\Year',
                    'DATE' => 'DoctrineExtensions\Query\Mysql\Date',
                    'IF' => 'DoctrineExtensions\Query\Mysql\IfElse',
                    'IF_NULL' => 'DoctrineExtensions\Query\Mysql\IfNull',
                    'DATE_FORMAT' => 'DoctrineExtensions\Query\Mysql\DateFormat',
                    'REGEXP' => 'DoctrineExtensions\Query\Mysql\Regexp',
                    'WEEK' => 'DoctrineExtensions\Query\Mysql\Week',
                ],
                // Generate proxies automatically (turn off for production)
                'generate_proxies'  => true,
            ]
        ]
    ],
];
