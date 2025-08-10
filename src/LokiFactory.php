<?php

namespace Williamtome\Loki;

use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Logger;

class LokiFactory
{
    public function __invoke(array $config): Logger
    {
        return new Logger(
            'loki',
            [
                new WhatFailureGroupHandler([
                    (new LokiHandler(
                        $config['configApi']['entrypoint'],
                        $config['configApi']['username'],
                        $config['configApi']['password'],
                        $config['level']
                    ))->setFormatter(new LokiFormatter($config['configApi']['globalLabels']))
                ])
            ]
        );
    }
}
