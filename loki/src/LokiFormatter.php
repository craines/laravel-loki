<?php

namespace Williamtome\Loki;

use Monolog\Formatter\NormalizerFormatter;
use Illuminate\Support\Facades\Request;
use Illuminate\Translation;
use Monolog\LogRecord;

class LokiFormatter extends NormalizerFormatter
{
    public function __construct(private array $globalLabels) {
        parent::__construct();
    }

    public function format(LogRecord|array $record): array
    {
        $message = $record['message'];
        if (isset($record['context']['exception'])) {
            $message = $message . $record['context']['exception'];
        }
        $backtrace = json_encode(array_slice(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 8));
        $customerName = getenv('CUSTOMER_NAME') ?? __('loki::validation.not_informed');
        $applicationNetwork = getenv('NETWORK') ?? __('loki::validation.not_informed');

        return [
            'stream' =>
                array_merge(
                    $this->globalLabels,
                    [
                        'channel' => $record['channel'],
                        'level' => $record['level_name'],
                        'host' => gethostname(),
                        'request'  => Request::all(),
                        'request_uri' => Request::path(),
                        'request_method' => Request::method(),
                        'customer_name' => $customerName,
                        'application_network' => $applicationNetwork
                    ]
                ),
            'values' => [
                [
                    (string) ($record['datetime']->getTimestamp() * 1000000000),
                    $this->getFormattedMessage($record, $message, $backtrace)
                ]
            ]
        ];
    }

    private function getFormattedMessage(array $record, string $message, string $backtrace): string
    {
        return $record['level_name'] === 'INFO'
            ? $record['datetime'] . ' ' . $record['level_name'] . ': ' . $message
            : $record['datetime'] . ' ' . $record['level_name'] . ': ' . $message . ' - Trace: ' . $backtrace;
    }
}
