<?php

namespace Memsource\Connector\Model\Logger\File;

class File extends \SplFileInfo
{
    /** @var string */
    const FILE_NAME = 'memsource_connector.log';

    /** @var string */
    const FILE_PATH = '/var/log/' . self::FILE_NAME;

    /**
     * @return string
     */
    public function getSizeInKB()
    {
        return number_format($this->getSize() / 1024, 3, '.', ' ');
    }

    /**
     * Get full path of file.
     * @param $root string root directory
     * @return string
     */
    public static function getFullPath($root)
    {
        return $root . self::FILE_PATH;
    }

    /**
     * Get content of file.
     * @return string|null
     */
    public function getContent()
    {
        $filePath = $this->getRealPath();

        if (file_exists($filePath)) {
            return file_get_contents($this->getRealPath()) ?: null;
        }

        return null;
    }
}
