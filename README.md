# google-search-console-crawl-indexed-urls-unlimited

### Description
`google-search-console-crawl-indexed-urls-unlimited` is a PHP tool designed to retrieve an unlimited number of indexed URLs from Google Search Console (GSC). The script uses the GSC API to bypass the typical 1,000 URL display limit in the GSC UI, allowing users to access and export all indexed URLs for a given website. This is especially useful for large sites where analyzing indexed URLs in bulk is necessary.

### Features
- **Unlimited URL Retrieval**: Retrieves all indexed URLs from GSC in batches, regardless of site size.
- **Excel Export**: Compiles retrieved URLs into a neatly organized Excel file for analysis or reporting.
- **Service Account Authentication**: Uses a secure Google service account for automated API access.

### Requirements
- **CREDENTIALS** credentials.json file is needed which you can add by downloading it from your GSC project file after allowing user rights.
- **PHP**: Version 7 or higher.
- **Google API PHP Client**: For authenticating and communicating with the GSC API.
- **PhpSpreadsheet**: For generating Excel files.

### Setup and Usage

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/google-search-console-crawl-indexed-urls-unlimited.git
   cd google-search-console-crawl-indexed-urls-unlimited
