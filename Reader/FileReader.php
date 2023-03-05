<?php

namespace TestOnly\Reader;

use TestOnly\Repository;

class FileReader
{
    private ReaderJson $readerJson;
    private ReaderXml $readerXml;

    public function __construct()
    {
        $this->readerJson = new ReaderJson;
        $this->readerXml = new ReaderXml;
    }

    /**
     * @param int $roundId
     * @param string $requestType
     * @return ReaderInterface|null
     * @throws \Exception
     */
    public function getData(int $roundId, string $requestType): ?ReaderInterface
    {
        $fileName = $this->getFileName($roundId, $requestType);
        return $this->getDataFromExtension($fileName['name'], $fileName['extension']);
    }

    /**
     * @param string $fileName
     * @param string $extension
     * @return ReaderInterface|null
     */
    private function getDataFromExtension(string $fileName, string $extension): ?ReaderInterface
    {
        switch($extension) {
            case "json":
                return $this->readerJson->getData($fileName);
            case "xml":
                return $this->readerXml->getData($fileName);
            default:
                return null;
        }
    }

    /**
     * @param int $roundId
     * @param string $requestType
     * @return array|null
     */
    private function getFileName(int $roundId, string $requestType): ?array
    {
        $fileName = $requestType.'_'.(strlen((string)$roundId) == 1 ? 0 : '').$roundId;
        return file_exists(Repository::DIR.'/'.$fileName.'.json') ? ['name' => $fileName.'.json', 'extension' => 'json'] :
            (file_exists(Repository::DIR.'/'.$fileName.'.xml') ? ['name' => $fileName.'.xml', 'extension' => 'xml'] :
                null);
    }
}