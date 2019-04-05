<?php

namespace App\Service\Storage;

use Psr\Log\LoggerInterface;

/**
 * @package App\Services\Storage
 */
class StorageService
{
    const FOLDER_DEPTH = -3;
    
    /** @var LoggerInterface */
    private $logger;
    /** @var string */
    private $root;
    /** @var string */
    private $bucket;

    function __construct(LoggerInterface $logger)
    {
        $this->root = getenv('DATA_STORAGE');
        $this->logger = $logger;
    }

    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }
    
    /**
     * Save some data to the file system
     */
    public function save(string $filename, $data)
    {
        // build full filename
        $filename = $this->buildFolder($filename) .'/'. $filename;

        // save
        file_put_contents(
            $filename,
            json_encode($data, JSON_NUMERIC_CHECK)
        );

        return $this;
    }

    /**
     * Load some data from the file system
     */
    public function load(string $filename, $default = false)
    {
        // build full filename
        $filename = $this->buildFolder($filename) .'/'. $filename;

        if (!file_exists($filename)) {
            return $default;
        }

        $data = json_decode(
            file_get_contents($filename)
        );

        if (empty($data) || json_last_error()) {
            if (json_last_error()) {
                $this->logger->error('Error JSON decoding: '. json_last_error_msg());
            }
            
            return $default;
        }

        return $data;
    }

    /**
     * Build a folder based on the filename
     */
    private function buildFolder(string $filename)
    {
        $filename = filter_var($filename, FILTER_SANITIZE_NUMBER_INT);
        $folder = implode('/', str_split(substr($filename, self::FOLDER_DEPTH)));
        $folder = "{$this->root}/{$this->bucket}/{$folder}";
    
        // create the folder if it does not exist.
        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        return $folder;
    }
}
