<?php
class Database
{
    private PDO $pdo;

    public function __construct($host, $dbname, $username, $password)
    {
        try {
            $this->pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getAuthorId($name)
    {
        $stmt = $this->pdo->prepare("SELECT id FROM authors WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($author) {
            return $author['id'];
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO authors (name) VALUES (:name)");
            $stmt->execute(['name' => $name]);
            return $this->pdo->lastInsertId();
        }
    }

    public function addOrUpdateBook($authorId, $title): void
    {
        $stmt = $this->pdo->prepare("SELECT id FROM books WHERE author_id = :author_id AND title = :title");
        $stmt->execute(['author_id' => $authorId, 'title' => $title]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            $stmt = $this->pdo->prepare("UPDATE books SET title = :title WHERE id = :id");
            $stmt->execute(['title' => $title, 'id' => $book['id']]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO books (author_id, title) VALUES (:author_id, :title)");
            $stmt->execute(['author_id' => $authorId, 'title' => $title]);
        }
    }

    public function fetchAllBooks(): void
    {
        $stmt = $this->pdo->prepare("SELECT b.title, a.name as author FROM books b JOIN authors a ON b.author_id = a.id ORDER BY a.name, b.title");
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($books) {
            echo "\nAll books and authors from DataBase:\n";
            foreach ($books as $book) {
                echo "Author: " . $book['author'] . " | Book name: " . $book['title'] . "\n";
            }
        } else {
            echo "There is no books in DataBase.\n";
        }
    }
}