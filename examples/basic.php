<?php
declare(strict_types=1);
namespace Pas2\CsvRecords;

require __DIR__ . "/../src/library.php";
$stream = fopen("php://temp", "w+");
fwrite($stream, "name,role\nAda,developer\n");
rewind($stream);
foreach (readRecords($stream) as $record) {
    print_r($record);
}
fclose($stream);
