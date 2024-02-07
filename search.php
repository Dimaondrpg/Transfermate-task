<?php
$host = 'localhost';
$db = 'postgres';
$user = 'postgres';
$pass = '2001';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$dsn = "pgsql:host=$host;dbname=$db";
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

$search = $_GET['search'] ?? '';


if (!empty($search)) {
    $stmt = $pdo->prepare('SELECT authors.name AS author_name, books.title AS book_title
                                    FROM authors
                                    LEFT JOIN books ON authors.id = books.author_id
                                    WHERE authors.name ILIKE ?
                                    ORDER BY authors.name');
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query('SELECT authors.name AS author_name, books.title AS book_title 
                                    FROM authors 
                                    LEFT JOIN books ON authors.id = books.author_id
                                    ORDER BY author_name');
}

$booksAndAuthors = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="Styles/styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book catalog</title>
</head>
<body>
<div class="container">
    <h2>Search of books by author</h2>
    <form action="" method="get">
        <label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                   placeholder="Enter the author's name">
        </label>
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($booksAndAuthors)): ?>
    <table>
        <thead>
        <tr>
            <th>Author</th>
            <th>Book</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($booksAndAuthors as $row):
            $bookTitle = $row['book_title'] ?: 'We don\'t have books for this author yet';?>
            <tr class="result-row">
                <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                <td><?php echo htmlspecialchars($bookTitle); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && $search): ?>
    <p>Sorry! We don't have this author in our database.</p>
<?php endif; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('.result-row');
        rows.forEach((row, index) => {
            setTimeout(() => {
                row.style.opacity = 1;
                row.style.transform = 'translateX(0)';
            }, 100 * index);
        });
    });
</script>
</body>
</html>