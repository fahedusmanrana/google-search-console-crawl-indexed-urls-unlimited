<?php
/**
 * Created by: Fahed Usman Rana
 * Created for getting unlimited number of URLs which are indexed by GSC
 */

require 'vendor/autoload.php';

use Google\Client;
use Google\Service\SearchConsole;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$SERVICE_ACCOUNT_FILE = 'credentials.json';
$SITE_URL = 'https://ceolawyer.com';

function getAllIndexedUrls($service, $siteUrl): array
{
    $urls = [];
    $startRow = 0;
    $rowsPerPage = 250;

    // I want to decide a time of last 15 days from current time
    $startDate = date('Y-m-d', strtotime('-15 days'));
    $endDate = date('Y-m-d');

    do {
        $requestBody = new Google\Service\SearchConsole\SearchAnalyticsQueryRequest([
            'startDate' => $startDate,
            'endDate' => $endDate,
            'startRow' => $startRow,
            'rowLimit' => $rowsPerPage,
            'dimensions' => ['page']
        ]);

        try {
            $response = $service->searchanalytics->query($siteUrl, $requestBody);
        } catch (\Google\Service\Exception $e) {
            echo 'API Request Error: ' . $e->getMessage();
            exit;
        }

        if (empty($response->rows)) {
            break;
        }

        foreach ($response->rows as $row) {
            $urls[] = $row->keys[0];
        }

        $startRow += $rowsPerPage;
        sleep(1);
    } while (count($response->rows) === $rowsPerPage);

    return $urls;
}

$client = new Client();
$client->addScope(Google\Service\SearchConsole::WEBMASTERS_READONLY);

try {
    $client->setAuthConfig($SERVICE_ACCOUNT_FILE);
} catch (\Google\Exception $e) {
    echo 'Error loading service account credentials: ' . $e->getMessage();
    exit;
}

$service = new SearchConsole($client);
$indexedUrls = getAllIndexedUrls($service, $SITE_URL);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Indexed URLs');
$sheet->setCellValue('A1', 'URL');

$rowNumber = 2;
foreach ($indexedUrls as $url) {
    $sheet->setCellValue('A' . $rowNumber, $url);
    $rowNumber++;
}

// I want to write this into a file which contains all indexed URLs
$writer = new Xlsx($spreadsheet);
$filename = 'indexed_urls.xlsx';
$writer->save($filename);

// Just show a message for overview of how many urls were done and file name
echo "Data saved to $filename with a total of " . count($indexedUrls) . " URLs." . PHP_EOL;
