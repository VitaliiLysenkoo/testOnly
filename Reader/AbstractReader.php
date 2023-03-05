<?php

namespace TestOnly\Reader;

use TestOnly\Repository;

abstract class AbstractReader implements ReaderInterface
{
    public string $roundId;
    public ?string $playerId;
    public ?int $reward;

    /**
     * @param string $file
     * @return ReaderInterface
     */
    public function getData(string $file): ReaderInterface
    {
        $this->getDataToObject($file);
        return $this;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getDataFile(string $fileName): string
    {
        return file_get_contents(Repository::DIR.'/'.$fileName);
    }
}