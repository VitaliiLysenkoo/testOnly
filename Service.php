<?php

declare(strict_types=1);

class Service
{
    const URL = 'https://int.dev.onlyplay.net/test_api/';

    private array $options;
    private string $key;
    private int $providerId;

    public function __construct(string $key, int $providerId)
    {
        $this->key = $key;
        $this->providerId = $providerId;
        $this->options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'timeout' => 3
            ]
        ];
    }

    /**
     * @param array $data
     * @param string $requestType
     * @return stdClass|null
     */
    public function sendRequest(array $data, string $requestType): ?stdClass
    {
        if($requestType === 'start_round') $this->generateRequestBodyStart($data);
        elseif($requestType === 'end_round') $this->generateRequestBodyEnd($data);
        else return $this->createOb('Invalid type round');

        $response = file_get_contents(
            self::URL . $requestType,
            false,
            stream_context_create($this->options)
        );

        if($response === false) return $this->createOb('HTTP request failed!');
        elseif(empty($response)) return $this->createOb('Response is empty.');

        // фикс ответа сервера
        $response = stristr($response, '{"');
        if(strpos($response, '"}') === false ) $response .='"}';

        return json_decode($response) ?? $this->createOb($response);
    }

    /**
     * @param string $json
     * @return string
     */
    private function generateSignature(string $json): string
    {
        return hash_hmac('sha256', $json, $this->key);
    }

    /**
     * @param array $data
     * @return void
     */
    private function generateRequestBodyStart(array $data): void
    {
        $requestData = [
            'round_id' => $data['roundId'] ?? $data['round-id'],
            'player_id' => $data['playerId'] ?? $data['player-id'],
            'provider_id' => $this->providerId,
        ];

        $requestData['sign'] = $this->generateSignature(json_encode($requestData));
        $this->options['http']['content'] = json_encode($requestData);
    }

    /**
     * @param array $data
     * @return void
     */
    private function generateRequestBodyEnd(array $data): void
    {
        $requestData = [
            'round_id' => $data['roundId'] ?? $data['round-id'],
            'reward' => $data['reward'],
            'provider_id' => $this->providerId,
        ];

        $requestData['sign'] = $this->generateSignature(json_encode($requestData));
        $this->options['http']['content'] = json_encode($requestData);
    }

    /**
     * @param string $text
     * @return stdClass
     */
    private function createOb(string $text): stdClass
    {
        $object= (new stdClass());
        $object->message = $text;
        $object->success = false;
        return $object;
    }
}
