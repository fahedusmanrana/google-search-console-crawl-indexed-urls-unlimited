# Google Search Console Indexed URLs Fetcher

Developed and maintained by Fahed Usman Rana (jogari.com)

### Description
The **Google Search Console Indexed URLs Fetcher** is a PHP-based tool designed to retrieve all indexed URLs from Google Search Console (GSC) along with the corresponding dates of search activity. Unlike the GSC UI, which limits displayed results to 1,000 URLs, this tool uses the GSC API to bypass such restrictions, making it suitable for large-scale websites requiring comprehensive indexing data.

### Features
- **Comprehensive URL Retrieval**: Fetches all URLs with search performance data from GSC, regardless of the site size.
- **Indexed Date Information**: Retrieves the dates of search activity for each URL within a specified time range.
- **Excel Export**: Outputs the results into a clean, organized Excel file for further analysis or reporting.
- **Secure API Access**: Utilizes Google Service Account authentication for secure, automated API interactions.

### Requirements
- **PHP**: Version 7.4 or higher.
- **Google API PHP Client**: For interfacing with the GSC API.
- **PhpSpreadsheet**: For creating Excel files.
- **Service Account Credentials**: A `credentials.json` file downloaded from your Google Cloud Project with appropriate GSC access.

### Setup and Usage

#### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/google-search-console-indexed-urls-fetcher.git
cd google-search-console-indexed-urls-fetcher
```

#### 2. Install Dependencies
Ensure you have Composer installed, then run:
```bash
composer install
```

#### 3. Add Credentials
Download the `credentials.json` file for your Google Cloud Project and place it in the project root directory.

#### 4. Configure the Script
Edit the following variables in `index.php`:
- **`$SERVICE_ACCOUNT_FILE`**: Path to your `credentials.json` file.
- **`$SITE_URL`**: Your verified GSC property (e.g., `https://example.com`).

#### 5. Run the Script
Execute the script to fetch indexed URLs:
```bash
php index.php
```
The results will be saved as an Excel file (`indexed_urls.xlsx`) in the project directory.

### Notes
- The script retrieves URLs that have had search activity during the specified date range. This confirms that the URLs are indexed.
- Adjust the date range in the script if necessary by modifying `$startDate` and `$endDate`.

### Troubleshooting
- **"No indexed URLs found"**: Ensure the `credentials.json` file has proper access and the GSC property matches your `$SITE_URL`.
- **Authentication Errors**: Verify that the service account is added as a user with appropriate permissions in your GSC property.
- **Duplicate URLs**: The script filters duplicate URLs to ensure unique results.

### License
This project is open-source and available under the MIT license.

