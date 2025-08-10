<?php

namespace Williamtome\Loki;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class LokiHandler extends AbstractProcessingHandler
{
    public function __construct(
        protected string $entrypoint,
        protected string $username = '',
        protected string $password = '',
        $level = Logger::DEBUG,
        $buble = true
    ) {
        parent::__construct($level, $buble);
    }

    protected function write(LogRecord|array $record): void
    {
        $httpClient = new Client([
            'auth' => [$this->username, $this->password],
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'base_uri' => $this->entrypoint
        ]);

        try {
            $httpClient->request(
                'POST',
                '/loki/api/v1/push',
                [
                    'json' => [
                        'streams' => [$record['formatted']]
                    ]
                ]
            );
        } catch (GuzzleException|Exception $e) {
            File::append(Storage::path('logs/laravel.log'), $e->getMessage() . PHP_EOL);
        }
    }
}
