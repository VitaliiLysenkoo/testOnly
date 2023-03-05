<?php

namespace TestOnly\Reader;

use SimpleXMLElement;

class ReaderXml extends AbstractReader
{
    /**
     * @param string $fileName
     * @return void
     * @throws \Exception
     */
    public function getDataToObject(string $fileName): void
    {
        $data = (array)(new SimpleXMLElement($this->getDataFile($fileName)));
        $this->playerId = $data['player-id'] ?? null;
        $this->reward = $data['reward'] ?? null;
        $this->roundId = $data['round-id'] ?? null;
    }
}