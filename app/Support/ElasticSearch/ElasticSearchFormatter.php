<?php



namespace App\Support\ElasticSearch;

use Monolog\Formatter\NormalizerFormatter;


class ElasticSearchFormatter extends NormalizerFormatter
{

    public function format(array $record): string
    {
        $message = [
            '@timestamp' => $this->normalize($record["datetime"]),
            'log' => [
                'level' => $record['level_name'],
                'logger' => $record['channel'],
            ],
        ];

        if(isset($record['message'])){
            $message['message'] = $record['message'];
        }

        return $this->toJson($message) . "\n";
    }
}
