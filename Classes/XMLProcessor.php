<?php

class XMLProcessor {
    private Database $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function process($filePath): void
    {
        $xml = simplexml_load_file($filePath);
        foreach ($xml->book as $book) {
            $authorName = (string)$book->author;
            $bookTitle = (string)$book->name;

            $authorId = $this->db->getAuthorId($authorName);
            $this->db->addOrUpdateBook($authorId, $bookTitle);
        }
        echo "File '$filePath' was successfully uploaded to DataBase.\n";
    }

    public function processDirectory($directory): void
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() == 'xml') {
                $this->process($file->getPathname());
            }
        }
    }
}