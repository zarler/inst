<?php

use Symfony\Component\Filesystem\Filesystem as Filesystem;

class Lib_FileSystem_FileSystem
{

    protected $fileSystem;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->fileSystem = new Filesystem();
        return $this;
    }


    /**
     * create file by absolute path
     */
    public function addFile($absPath, $mode=0777)
    {
        if (!is_dir(dirname($absPath))) {
            $this->fileSystem->mkdir(dirname($absPath), $mode);
        }
        $this->fileSystem->touch($absPath);
        return $this->fileSystem->exists($absPath);
    }

}
