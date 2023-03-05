<?php

namespace TestOnly\Reader;

class ReaderJson extends AbstractReader
{
    /**
     * @param string $fileName
     * @return void
     */
    public function getDataToObject(string $fileName): void
    {
        $data = json_decode($this->getDataFile($fileName));
        $this->playerId = $data->playerId ?? null;
        $this->reward = $data->reward ?? null;
        $this->roundId = $data->roundId ?? null;
    }
}