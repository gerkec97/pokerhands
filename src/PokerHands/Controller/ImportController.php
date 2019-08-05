<?php

namespace PokerHands\Controller;

use PokerHands\Model\Hand;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use PokerHands\Validator\Card as CardValidator;
use PokerHands\Model\Card;

class ImportController
{
    private $container;
    private $invalidHands = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function fromFile(Request $req, Response $res, $args)
    {
        $directory = $this->container->get('settings')['upload_directory'];

        $uploadedFiles = $req->getUploadedFiles();

        // handle single input with single file upload
        $uploadedFile = $uploadedFiles['hands_file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory, $uploadedFile);

            $this->processFile($directory . DIRECTORY_SEPARATOR . $filename);

            $res->write('uploaded ' . $filename . '<br/>');
        }

        return $res->withRedirect('/', 301);
    }

    /**
     * Reads the input file and passes each row down to the GameRoundService for processing
     * Invalid rows are collected for error handling
     *
     * @param string $filename
     */
    private function processFile(string $filepath)
    {
        $file = new \SplFileObject($filepath);
        $service = $this->container->get('gameRoundService');

        while (!$file->eof()) {
            $cards = $file->fgetcsv(' ');
            $cards = array_map('trim', $cards);

            if (!$service->importGameRound($cards)) {
                $this->invalidHands[] = implode(' ', $cards);
            }
        }
    }

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string $directory directory to which the file is moved
     * @param UploadedFile $uploadedFile file uploaded file to move
     * @return string filename of moved file
     */
    private function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}