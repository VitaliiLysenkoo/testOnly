<?php

declare(strict_types=1);

namespace TestOnly;

use Exception;
use TestOnly\Reader\FileReader;

class Repository
{
    const DIR = 'requests';
    const ROUNDS = 20;

    private Service $service;
    private Logger $logger;
    private FileReader $reader;

    public function __construct(int $providerId, string $key)
    {
        $this->service = new Service($key, $providerId);
        $this->logger = new Logger;
        $this->reader = new FileReader;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function startGame(): void
    {
        for($i = 1; $i <= self::ROUNDS; $i++){
            $this->endpoint($i, 'start_round');
            $this->endpoint($i, 'end_round');
        }
    }

    /**
     * @param int $roundId
     * @param string $requestType
     * @return void
     * @throws Exception
     */
    private function endpoint(int $roundId, string $requestType = 'start_round'): void
    {
        $data = $this->reader->getData($roundId, $requestType);

        if(!empty($data)) {
            $response = $this->service->sendRequest($data, $requestType);
            $this->logger->log($response, $requestType);
        }
    }
}