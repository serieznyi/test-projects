<?php


namespace App\Service;

use App\Form\ImageForm;
use RuntimeException;

/**
 * @package App\Service
 */
class PhoneImageService
{
    /**
     * @var string path to temp directory
     */
    private $tmpDirPath;
    /**
     * @var AuthService
     */
    private $authService;
    /**
     * @var string
     */
    private $publicDir;
    /**
     * @var string
     */
    private $storageDir;

    /**
     * PhoneImageService constructor.
     * @param string $tmpDirPath
     * @param string $storageDir
     * @param string $publicDir
     * @param AuthService $authService
     */
    public function __construct($tmpDirPath, $storageDir, $publicDir, AuthService $authService)
    {
        if (!is_dir($tmpDirPath)) {
            throw new RuntimeException("Tmp dir not exists: {$tmpDirPath}");
        }

        $this->tmpDirPath = rtrim($tmpDirPath, DIRECTORY_SEPARATOR);
        $this->authService = $authService;
        $this->publicDir = $publicDir;

        if (!is_dir($storageDir)) {
            throw new RuntimeException("Storage dir not exists: {$storageDir}");
        }

        $this->storageDir = $storageDir;
    }

    /**
     * @param ImageForm $form
     * @return array
     */
    public function saveInTemp(ImageForm $form)
    {
        $userId = $this->authService->getUserIdentifier();

        $userTmpDir = $this->tmpDirPath . DIRECTORY_SEPARATOR . $userId;

        if (!is_dir($userTmpDir)) {
            if (!mkdir($userTmpDir) && !is_dir($userTmpDir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $userTmpDir));
            }
        }

        $newFilePath = $userTmpDir . DIRECTORY_SEPARATOR .  $this->buildFileName($form);
        if (!file_exists($newFilePath)) {
            copy($form->path, $newFilePath);
        }

        return [
            'path' => substr($newFilePath, strlen($this->tmpDirPath)),
            'name' => $form->name,
            'type' => $form->type,
            'preview' => $this->buildPreviewLink($newFilePath),
        ];
    }

    /**
     * @param $newFilePath
     * @return false|string
     */
    private function buildPreviewLink($newFilePath)
    {
        return  '/' . substr($newFilePath, strlen($this->publicDir));
    }

    /**
     * @param ImageForm $form
     * @return string
     */
    private function buildFileName(ImageForm $form)
    {
        static $extMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/svg+xml' => 'svg',
            'image/gif' => 'gif',
        ];

        $fileName = sha1_file($form->path);

        $fileExt = array_key_exists($form->type, $extMap) ? $extMap[$form->type] : null;

        return "{$fileName}.{$fileExt}";
    }

    /**
     * @param $relativeImagePath
     */
    public function moveFileInStorage($relativeImagePath)
    {
        $userStorageDir = $this->storageDir . DIRECTORY_SEPARATOR . $this->authService->getUserIdentifier();

        if (!is_dir($userStorageDir)) {
            if (!mkdir($userStorageDir) && !is_dir($userStorageDir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $userStorageDir));
            }
        }

        $fileName = basename($relativeImagePath);

        copy(
            $this->tmpDirPath . DIRECTORY_SEPARATOR . $relativeImagePath,
            $userStorageDir . DIRECTORY_SEPARATOR . $fileName
        );
    }
}