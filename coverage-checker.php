<?php

$inputFile = $argv[1];

if (!file_exists($inputFile)) {
    throw new InvalidArgumentException('Invalid input file provided');
}

$xml = new SimpleXMLElement(file_get_contents($inputFile));
$metrics = $xml->xpath('//metrics');
$totalElements = 0;
$checkedElements = 0;

foreach ($metrics as $metric) {
    $totalElements += (int)$metric['elements'];
    $checkedElements += (int)$metric['coveredelements'];
}

$coverage = ($checkedElements / $totalElements) * 100;

if ($coverage < 100) {
    echo 'Code coverage is ' . $coverage . '%, which is below the accepted 100%';

    throw new Exception('Code coverage is ' . $coverage . '%, which is below the accepted 100%');
}

echo 'Code coverage is ' . $coverage . '% - OK!' . PHP_EOL;
