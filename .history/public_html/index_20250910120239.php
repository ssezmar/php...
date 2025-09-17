<h1>Hello Cloudreach!</h1>
<h4>Attempting MySQL connection from PHP...</h4>
<script>console.log("js is so active")</script>
<?php
$host = 'mysql';
$user = 'root';
$pass = 'rootpassword';
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected to MySQL successfully!<br>";
}

// Получаем список всех баз данных
$result_dbs = $conn->query("SHOW DATABASES");

if ($result_dbs && $result_dbs->num_rows > 0) {
    while ($row = $result_dbs->fetch_row()) {
        $db_name = $row[0];
        echo "<h3>Tables in database: $db_name</h3>";

        // Переключаемся на каждую базу данных
        $conn->select_db($db_name);

        // Получаем таблицы в текущей БД
        $result_tables = $conn->query("SHOW TABLES");

        if ($result_tables && $result_tables->num_rows > 0) {
            echo "<ul>";
            while ($table_row = $result_tables->fetch_row()) {
                echo "<li>" . $table_row[0] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "No tables found in database '$db_name'.<br>";
        }
    }
} else {
    echo "No databases found.";
}

$conn->close();
?>