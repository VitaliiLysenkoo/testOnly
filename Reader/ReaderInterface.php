<?php

namespace TestOnly\Reader;

interface ReaderInterface
{
    /**
     * @param string $file
     * @return ReaderInterface
     */
    public function getData(string $file): ReaderInterface;

    /**
     * @param string $fileName
     * @return string
     */
    public function getDataFile(string $fileName): string;

    /**
     * @param string $fileName
     * @return void
     */
    public function getDataToObject(string $fileName): void;
}