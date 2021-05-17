<?php
namespace  App\Services;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader{
    private ContainerInterface $container;


    /**
     * FileUploader constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public  function uploadFile(UploadedFile $file)
    {

        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move(
            $this->container->getParameter('uploads_dir'),
            $filename
        );
            return $filename;
    }




}



