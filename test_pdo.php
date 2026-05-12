<?php
echo "Testing MySQL connection to 127.0.0.1:3307...\n";
$start = microtime(true);

try {
    $pdo = new PDO(
        'mysql:host=127.0.0.1;port=3307',
        'root',
        '',
        [PDO::ATTR_TIMEOUT => 5]
    );
    $elapsed = round(microtime(true) - $start, 2);
    echo "Connected in {$elapsed}s\n";
    
    $result = $pdo->query("SHOW DATABASES");
    if ($result) {
        echo "Databases:\n";
        while ($row = $result->fetch()) {
            echo "  - " . $row[0] . "\n";
        }
    }
} catch (PDOException $e) {
    $elapsed = round(microtime(true) - $start, 2);
    echo "FAILED after {$elapsed}s\n";
    echo "Error: " . $e->getMessage() . "\n";
}
