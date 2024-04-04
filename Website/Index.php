<!DOCTYPE html>
<html lang="de">
<head>
    <title>Meine Seite</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<header>
    <h1>Willkommen auf meiner Seite</h1>
    <nav>
        <ul>
            <li><a href="#">Startseite</a></li>
            <li><a href="#">Über mich</a></li>
            <li><a href="#">Projekte</a></li>
            <li><a href="#">Kontakt</a></li>
        </ul>
    </nav>
</header>





<main>

    <form action="Index.php" method="get">
        <label>
            <input type="text" name="search" placeholder="Suche nach Büchern...">
        </label>
        <input type="submit" value="Suchen">
    </form>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "books";

    $Such_kriterien = isset($_GET['search']) ? $_GET['search'] : '';

    // erstellt die Verbindung
    $conn = new mysqli($servername, $username, $password, $dbname);

    // überprüft die Verbindung
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $items_per_page = 12;
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;

    //rechnet die anzahl bücher aus
    $sql_total = "SELECT COUNT(*) as total FROM buecher";
    $result_total = $conn->query($sql_total);
    $row_total = $result_total->fetch_assoc();
    $total_books = $row_total['total'];

    // rechnet die totale seiten anzahl aus
    $total_pages = ceil($total_books / $items_per_page);

    $sql = "SELECT id, katalog, nummer, kurztitle, kategorie, verkauft, kaufer, autor, title, sprache, foto, verfasser, zustand FROM buecher WHERE kurztitle LIKE '$Such_kriterien%' LIMIT $items_per_page OFFSET $offset";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Bilder Reihen
        while($row = $result->fetch_assoc()) {
            echo '<div class="book-item">';
            echo '<img src="/Bilder/cover.jpg" alt="' . $row["foto"] . '" alt="' . $row["kurztitle"] . '">';
            echo '<h2>' . $row["kurztitle"] . '</h2>';
            echo '<div class="book-details">';
            echo '<p>' . $row["kurztitle"] . '</p>';
            echo '<p>Autor: ' . $row["autor"] . '</p>';
            echo '<p>Kategorie: ' . $row["kategorie"] . '</p>';
            echo '<p>Sprache: ' . $row["sprache"] . '</p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "0 results";
    }





    echo '<div class="pagination">';

    // Rechnet die Start und End Seiten aus
    $start_page = max(1, $current_page - 5);
    $end_page = min($total_pages, $current_page + 5);

    // fügt ein "previous" link hinzu
    if ($current_page > 1) {
        $prev_page = max(1, $current_page - 15);
        echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?page=$prev_page\"><<</a>";
    }

    // fügt die Seitenzahlen hinzu
    for ($i = $start_page; $i <= $end_page; $i++) {
        if ($i == $current_page) {
            echo "<span class=\"page-number current-page\">$i</span>";
        } else {
            echo "<a class=\"page-number\" href=\"$_SERVER[PHP_SELF]?page=$i\">$i</a>";
        }
    }

    // fügt ein "next" link hinzu
    if ($current_page < $total_pages) {
        $next_page = min($total_pages, $current_page + 15);
        echo "<a class=\"page-arrow\" href=\"$_SERVER[PHP_SELF]?page=$next_page\">>></a>";
    }

    echo '</div>';

    $conn->close();
    ?>
</main>

<footer>
    <div class="footer-content">
        <p>© 2024 Meine Seite. Alle Rechte vorbehalten.</p>
        <ul class="footer-links">
            <li><a href="#">Impressum</a></li>
            <li><a href="#">Datenschutz</a></li>
            <li><a href="#">Kontakt</a></li>
        </ul>
    </div>
</footer>
</body>
</html>