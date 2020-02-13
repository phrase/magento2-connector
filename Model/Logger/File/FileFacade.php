<?php

namespace Memsource\Connector\Model\Logger\File;

use Magento\Framework\Filesystem\DirectoryList;

class FileFacade
{
    /** @var DirectoryList */
    private $directoryList;

    public function __construct(DirectoryList $directoryList)
    {
        $this->directoryList = $directoryList;
    }

    /**
     * Get object with File.
     * @return File
     */
    public function get()
    {
        $filePath = File::getFullPath($this->directoryList->getRoot());

        if (!is_file($filePath)) {
            $logFile = fopen($filePath, 'w');
            fclose($logFile);
        }

        return new File($filePath);
    }

    /**
     * Empty file.
     * @return bool
     */
    public function emptyFile()
    {
        $filePath = File::getFullPath($this->directoryList->getRoot());

        if (file_exists($filePath)) {
            $file = fopen($filePath, 'r+');

            if ($file !== false) {
                ftruncate($file, 0);
                fclose($file);
            }
        }

        return true;
    }
}
