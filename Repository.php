<?php

declare(strict_types=1);

require_once('Service.php');
require_once('Logger.php');

class Repository
{
    const DIR = 'requests';

    private Service $service;
    private Logger $logger;

    public function __construct(int $providerId, string $key)
    {
        $this->service = new Service($key, $providerId);
        $this->logger = new Logger();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function start(): void
    {
        $files = scandir(self::DIR);

        foreach ($files as $key => $fileName)
        {
            if($key < 2) continue;

            $extension = explode(".", $fileName);
            $data = $this->getData($fileName, $extension[1]);

            if(empty($data)) continue;

            $requestType = $this->getTypeEndpoint($extension[0]);
            $response = $this->service->sendRequest($data, $requestType);

            $this->logger->log($response, $requestType);
        }
    }

    /**
     * @param string $fileName
     * @param string $extension
     * @return array
     * @throws Exception
     */
    private function getData(string $fileName, string $extension): ?array
    {
        switch($extension) {
            case "json":
                return $this->getDataJson($fileName);
            case "xml":
                return $this->getDataXml($fileName);
            default:
                return null;
        }
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function getDataFile(string $fileName): string
    {
        return file_get_contents(self::DIR.'/'.$fileName);
    }

    /**
     * @param string $fileName
     * @return array
     */
    private function getDataJson(string $fileName): array
    {
        return (array)json_decode($this->getDataFile($fileName));
    }

    /**
     * @param string $fileName
     * @return array
     * @throws Exception
     */
    private function getDataXml(string $fileName): array
    {
        return (array)(new SimpleXMLElement($this->getDataFile($fileName)));
    }

    /**
     * @param string $fileName
     * @return string|null
     */
    private function getTypeEndpoint (string $fileName): ?string
    {
        if(strpos($fileName, 'start_round') === 0 ) return 'start_round';
        elseif(strpos($fileName, 'end_round') === 0) return 'end_round';
        else return null;
    }
}
