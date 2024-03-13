<?php

namespace Alura\Doctrine\Helper;

use Doctrine\DBAL\Logging\Middleware;
use Doctrine\Migrations\Tools\Console\ConsoleLogger;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Console\Output\ConsoleOutput;

class EntityManagerCreator
{
    public static function createEntityManager(): EntityManager
    {
        // Create a simple "default" Doctrine ORM configuration for Attributes
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__ . "/.."],
            true,
        );

        $consoleOutput = new ConsoleOutput(ConsoleOutput::VERBOSITY_DEBUG);
        $consoleLogger = new ConsoleLogger($consoleOutput);
        $logMiddleware = new Middleware($consoleLogger);
        $config->setMiddlewares([
            $logMiddleware
        ]);

        $cacheDirectory = __DIR__ . '/../../var/cache';
        $config->setMetadataCache(new PhpFilesAdapter(
            namespace: 'metadata_cache',
            directory: $cacheDirectory
        ));

        $config->setQueryCache(new PhpFilesAdapter(
            namespace: 'query_cache',
            directory: $cacheDirectory
        ));

        $config->setResultCache(new PhpFilesAdapter(
            namespace: 'result_cache',
            directory: $cacheDirectory
        ));

        // configuring the database connection
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_mysql',
            'host' => '172.17.0.2',
            'port' => '3306',
            'dbname' => 'students',
            'user' => 'root',
            'password' => 'rootpw',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',

        ], $config);

        // obtaining the entity manager
        return new EntityManager($connection, $config);
    }
}
