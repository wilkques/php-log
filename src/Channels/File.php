<?php

namespace Wilkques\Log\Channels;

use Wilkques\Filesystem\Filesystem;

class File
{
    /**
     * log name
     * 
     * @var string
     */
    protected $name = 'system.log';

    /**
     * log directory
     * 
     * @var string
     */
    protected $directory = './storage/logs';
    
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Octal representation of the cache file permissions.
     *
     * @var int|null
     */
    protected $filePermission;

    /**
     * @param Filesystem $filesystem
     * @param string $fileName
     * @param string $directory
     */
    public function __construct(Filesystem $filesystem, $directory = './storage/logs', $filePermission = null)
    {
        $this->setDirectory($directory);

        $this->setFilePermission($filePermission);

        $this->filesystem = $filesystem;
    }

    /**
     * @param int $filePermission
     * 
     * @return static
     */
    public function setFilePermission($filePermission)
    {
        $this->filePermission = $filePermission;

        return $this;
    }

    /**
     * @return int
     */
    public function getFilePermission()
    {
        return $this->filePermission;
    }

    /**
     * @param string $directory
     * 
     * @return static
     */
    public function setDirectory($directory = './storage/logs')
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $logName
     * 
     * @return static
     */
    public function logName($logName = 'system.log')
    {
        $this->name = $logName;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompilerPath()
    {
        return $this->getDirectory() . '/' . $this->name;
    }

    /**
     * Create the file log directory if necessary.
     *
     * @param  string  $path
     * @return void
     */
    protected function ensureLogDirectoryExists($path)
    {
        $directory = dirname($path);

        if (! $this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory, 0777, true, true);

            // We're creating two levels of directories (e.g. 7e/24), so we check them both...
            $this->ensurePermissionsAreCorrect($directory);
            $this->ensurePermissionsAreCorrect(dirname($directory));
        }
    }

    /**
     * Ensure the created node has the correct permissions.
     *
     * @param  string  $path
     * @return void
     */
    protected function ensurePermissionsAreCorrect($path)
    {
        if (is_null($this->getFilePermission()) ||
            intval($this->filesystem->chmod($path), 8) == $this->getFilePermission()) {
            return;
        }

        $this->filesystem->chmod($path, $this->getFilePermission());
    }

    /**
     * @param string $message
     * 
     * @return static
     */
    public function logger($message)
    {
        $this->ensureLogDirectoryExists($path = $this->getCompilerPath());

        $result = $this->filesystem->append($path, $message);

        return $result !== false && $result > 0;
    }
}