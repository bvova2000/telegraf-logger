<?php

require __DIR__ . '/vendor/autoload.php';

use Elasticsearch\ClientBuilder;
use MongoDB\Client as MongoClient;

// Настройка MongoDB
$mongo = new MongoClient('mongodb://mongo:27017');
$mongoCollection = $mongo->selectCollection('test', 'metrics');

// Настройка Elasticsearch
$elasticsearch = ClientBuilder::create()
    ->setHosts(['http://elasticsearch:9200'])
    ->build();

// Определяем режим (CLI или Web)
$isCli = php_sapi_name() === 'cli';
$runtimeLimit = 5; // Лимит выполнения в веб-режиме (секунды)
$startTime = microtime(true);

// Флаги для статистики
$ops = 0;
$mongoOps = 0;
$esOps = 0;

while (true) {
    if (!$isCli && (microtime(true) - $startTime > $runtimeLimit)) {
        break;
    }

    $time = random_int(100, 400);
    $types = ['search', 'book', 'login', 'logout'];
    $type = $types[random_int(0, 3)];
    $delta = random_int(1, 5);

    $mongoCollection->insertOne([
        'type' => $type,
        'delta' => $delta,
        'time' => $time,
        'created_at' => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp() * 1000),
    ]);
    ++$mongoOps;

    $elasticsearch->index([
        'index' => 'metrics',
        'body' => [
            'type' => $type,
            'delta' => $delta,
            'time' => $time,
            'timestamp' => date('c'),
        ],
    ]);
    ++$esOps;

    ++$ops;

    //задержка между операциями
    usleep(random_int(5, 55) * 1000);
}

if (!$isCli) {
    http_response_code(200);
    echo json_encode([
        'status' => 'ok',
        'ops' => $ops,
        'mongo_ops' => $mongoOps,
        'es_ops' => $esOps,
        'runtime' => microtime(true) - $startTime,
    ]);
}
