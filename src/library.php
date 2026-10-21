<?php
declare(strict_types=1);
namespace Pas2\CsvRecords;

function readRecords($stream, string $delimiter = ","): \Generator
{
    if (!is_resource($stream) || strlen($delimiter) !== 1) {
        throw new \InvalidArgumentException(
            "Readable stream and single-byte delimiter required",
        );
    }
    $headers = fgetcsv($stream, null, $delimiter, '"', "");
    if ($headers === false) {
        return;
    }
    if (
        in_array(null, $headers, true) ||
        in_array("", $headers, true) ||
        count(array_unique($headers)) !== count($headers)
    ) {
        throw new \UnexpectedValueException(
            "Headers must be non-empty and unique",
        );
    }
    while (($row = fgetcsv($stream, null, $delimiter, '"', "")) !== false) {
        if ($row === [null]) {
            continue;
        }
        if (count($row) !== count($headers)) {
            throw new \UnexpectedValueException(
                "CSV row width differs from header",
            );
        }
        yield array_combine($headers, $row);
    }
}
