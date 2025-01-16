<?php
/**
 * Retrieve Indexed URLs from Google Search Console with Indexed Dates
 * Author: Created and maintained by Fahed Usman Rana
 */

require 'vendor/autoload.php';

use Google\Client;
use Google\Service\SearchConsole;
use Google\Service\SearchConsole\SearchAnalyticsQueryRequest;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$SERVICE_ACCOUNT_FILE = 'credentials.json';
$SITE_URL = 'https://ceolawyer.com'; // Replace with your GSC property
$LOG_FILE = __DIR__ . '/gsc_logs.log';

// Ensure the log file exists and is writable
if (!file_exists($LOG_FILE)) {
    file_put_contents($LOG_FILE, "Log file created on: " . date('Y-m-d H:i:s') . "\n");
}
if (!is_writable($LOG_FILE)) {
    die("Log file is not writable: $LOG_FILE\n");
}

function initializeGoogleClient($serviceAccountFile): Client
{
    $client = new Client();
    $client->setAuthConfig($serviceAccountFile);
    $client->addScope(SearchConsole::WEBMASTERS_READONLY);
    $client->setAccessType('offline');

    return $client;
}

function fetchIndexedUrls(SearchConsole $service, $siteUrl): array
{
    $urls = [];
    $uniqueUrls = [];
    $startRow = 0;
    $rowsPerPage = 500;

    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 days'));

    do {
        try {
            $queryRequest = new SearchAnalyticsQueryRequest([
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dimensions' => ['page', 'date'],
                'rowLimit' => $rowsPerPage,
                'startRow' => $startRow
            ]);

            $response = $service->searchanalytics->query($siteUrl, $queryRequest);

            if (!empty($response->rows)) {
                foreach ($response->rows as $row) {
                    $url = $row['keys'][0];
                    $date = $row['keys'][1] ?? $endDate;

                    if (!isset($uniqueUrls[$url])) {
                        $uniqueUrls[$url] = true;
                        $urls[] = [
                            'url' => $url,
                            'date' => $date
                        ];
                    }
                }

                $startRow += $rowsPerPage;
            } else {
                break;
            }
        } catch (Exception $e) {
            error_log("Error fetching indexed URLs: " . $e->getMessage() . "\n", 3, $LOG_FILE);
            break;
        }
    } while (true);

    return $urls;
}

function exportToExcel(array $data, $filePath): void
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Indexed URLs');

    // Set header row
    $sheet->setCellValue('A1', 'URL');
    $sheet->setCellValue('B1', 'Recent Impression or click date (Definitely indexed page)');

    // Add data rows
    $row = 2;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item['url']);
        $sheet->setCellValue('B' . $row, $item['date']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);
}

try {
    $client = initializeGoogleClient($SERVICE_ACCOUNT_FILE);
    $service = new SearchConsole($client);

    $indexedUrls = fetchIndexedUrls($service, $SITE_URL);

    if (empty($indexedUrls)) {
        echo "No indexed URLs found.\n";
    } else {
        $outputFile = __DIR__ . '/indexed_urls.xlsx';
        exportToExcel($indexedUrls, $outputFile);

        echo "Indexed URLs have been saved to: $outputFile\n";
    }
} catch (Exception $e) {
    error_log("Critical error: " . $e->getMessage() . "\n", 3, $LOG_FILE);
    echo "An error occurred. Check the log file for details.\n";
}
