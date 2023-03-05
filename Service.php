<?php

declare(strict_types=1);

namespace TestOnly;

use stdClass;
use TestOnly\Reader\ReaderInterface;

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
        $this->options = array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        );
    }

    /**
     * @param ReaderInterface $data
     * @param string $requestType
     * @return stdClass|null
     */
    public function sendRequest(ReaderInterface $data, string $requestType): ?stdClass
    {
        $this->generateRequestBody($data, $requestType);

        $ch = curl_init();
        curl_setopt_array($ch, $this->options);
        $response = curl_exec($ch);
        curl_close($ch);

        if($response === false) return $this->createOb('HTTP request failed!');
        return !empty($response) ? (object)json_decode($response) : $this->createOb('Response is empty');
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
     * @param ReaderInterface $data
     * @param string $requestType
     * @return void
     */
    private function generateRequestBody(ReaderInterface $data, string $requestType): void
    {
        $requestData = [
            'round_id' => $data->roundId,
            'provider_id' => $this->providerId,
        ];

        if ($requestType === 'start_round')  $requestData['player_id'] = $data->playerId;
        elseif ($requestType === 'end_round')  $requestData['reward'] = $data->reward;

        $requestData['sign'] = $this->generateSignature(json_encode($requestData));

        $this->options[CURLOPT_POSTFIELDS] = json_encode($requestData);
        $this->options[CURLOPT_URL] = self::URL . $requestType;
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
