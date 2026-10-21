<?php
declare(strict_types=1);
namespace Pas2\CsvRecords;

require __DIR__ . "/../src/library.php";
function check(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException($message);
    }
}
function fails(callable $fn): void
{
    try {
        $fn();
    } catch (\Throwable) {
        return;
    }
    throw new \RuntimeException("Expected failure");
}
function streamOf(string $text)
{
    $s = fopen("php://temp", "w+");
    fwrite($s, $text);
    rewind($s);
    return $s;
}
$s = streamOf("name,note\nAda,\"a,b\"\n");
check(
    iterator_to_array(readRecords($s))[0] === [
        "name" => "Ada",
        "note" => "a,b",
    ],
    "quoted field",
);
fclose($s);
$s = streamOf("a,a\n1,2\n");
fails(fn() => iterator_to_array(readRecords($s)));
fclose($s);
$s = streamOf("a,b\n1\n");
fails(fn() => iterator_to_array(readRecords($s)));
fclose($s);
$s = streamOf("");
check(iterator_to_array(readRecords($s)) === [], "empty");
fclose($s);
echo "All checks passed\n";
