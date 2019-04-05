<?php

namespace App\Service;

use App\Kernel;
use App\Mapper\ErrorCode;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\ImageManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UploadService
{
    private const MAX_FILE_SIZE = (1024 * 1024 * 15);
    private const VALID_EXTENSIONS = [
        'png', 'jpg', 'jpeg', 'gif'
    ];

    /** @var ImageRepository */
    private $repo;

    /** @var string */
    private $projectRootDir;
    private $tempDirectory;
    private $saveDirectory;

    public function __construct(ImageRepository $repo, $projectRootDir)
    {
        $this->projectRootDir = $projectRootDir;
        $this->tempDirectory = $this->projectRootDir . getenv('STORAGE_FOLDER') .'/temp';
        $this->saveDirectory = $this->projectRootDir . getenv('STORAGE_FOLDER') .'/images';
        $this->repo = $repo;
    }

    public function save(Request $request)
    {
        // move all files (some day i might support multiplefiles
        /** @var UploadedFile $originalFile */
        foreach ($request->files as $originalFile) {
            // get file extension
            $fileExtension = strtolower(pathinfo($originalFile->getClientOriginalName())['extension']);

            $this->validateExtension($fileExtension);
            $this->validateFileSize($originalFile->getSize());

            $tempFilename = sprintf('%s.%s', Uuid::uuid4()->toString(), $fileExtension);

            // move file to temp location
            $originalFile->move($this->tempDirectory, $tempFilename);

            // get hash
            $hash = sha1_file("{$this->tempDirectory}/{$tempFilename}");

            // check if exists
            if ($this->repo->findOneBy([ 'hash' => $hash ])) {
                unlink("{$this->tempDirectory}/{$tempFilename}");
                throw new \Exception('Image already exists');
            } else {
                $filename = Uuid::uuid4()->toString();

                // create full filenames
                $filenameThumbnail = "{$filename}.thumb.jpg";
                $filename = "{$filename}.jpg";

                // get save directory
                $directory = $this->getFullDirectory($hash);

                // manipulate
                $manager = new ImageManager(['driver' => 'gd']);
                $img = $manager->make("{$this->tempDirectory}/{$tempFilename}");

                // compress
                $img->resize(null, 1080, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("{$directory}/{$filename}", 75);

                // create metadata
                $meta = [
                    'filetype' => $fileExtension,
                    'filesize' => $img->filesize(),
                    'width' => $img->width(),
                    'height' => $img->height(),
                ];

                // compress to 75% and create a 250px thumbnail
                $img->resize(null, 250, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("{$directory}/{$filenameThumbnail}", 75);

                // delete temp
                unlink("{$this->tempDirectory}/{$tempFilename}");

                return [
                    'filename' => $filename,
                    'filenameThumbnail' => $filenameThumbnail,
                    'filenameOriginal' => $originalFile->getClientOriginalName(),
                    'directory' => $this->getSafeDirectory($directory),
                    'hash' => $hash,
                    'meta' => $meta
                ];
            }
        }
    }

    public function getFullFilePath(string $directory, string $filename): string
    {
        return "{$this->saveDirectory}/{$directory}/{$filename}";
    }

    public function getNoImageFile(): string
    {
        return "{$this->projectRootDir}/public/noicon.png";
    }

    private function getSafeDirectory(string $directory): string
    {
        return str_ireplace($this->saveDirectory, null, $directory);
    }

    private function getFullDirectory(string $hash): string
    {
        $folders = str_split($hash, 3);
        $directory = "{$this->saveDirectory}/{$folders[0]}/{$folders[1]}/";

        // make directory
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return $directory;
    }

    /**
     * @throws \Exception
     */
    private function validateExtension($fileExtension): void
    {
        if (!in_array($fileExtension, self::VALID_EXTENSIONS)) {
            throw new \Exception('Invalid file extension');
        }
    }

    /**
     * @throws \Exception
     */
    private function validateFileSize($size): void
    {
        if ($size > self::MAX_FILE_SIZE) {
            throw new \Exception('Invalid file size');
        }
    }
}
