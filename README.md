Below is a complete, professional `README.md` in English that explains your webapp in detail:

---


# Real Visits Simulator (PHP Proxy + iFrame)

This project is a PHP-based web application developed by **Bocaletto Luca** (GitHub: `bocaletto-luca`) that simulates real page visits. It uses a reverse proxy to load a target URL inside an `<iframe>`, generating simulated traffic that appears as a full page load.

## Overview

The Real Visits Simulator is designed to mimic genuine user visits by reloading a target URL at defined intervals. The proxy functionality is built directly into the PHP script, making it easy to deploy and use without additional components. This solution can be utilized for testing, demonstration purposes, or exploring load behaviors, always with a commitment to responsible use.

## Features

- **Integrated Reverse Proxy:**  
  - Activates when the URL is appended with `?proxy=1&url=...`.  
  - Validates the supplied URL and uses cURL to fetch the target page's content.  
  - Strips out any `Content-Security-Policy` meta tags from the HTML to avoid undesired restrictions.

- **User-Friendly Interface:**  
  - A clean Bootstrap-based layout allows users to specify the target URL, the interval (in seconds) between refreshes, and the total number of simulated visits.
  - A real-time log panel is provided to display visit activity with timestamps.
  - Dedicated buttons to start, pause/resume, and stop the simulation, giving complete control to the user.

- **Dynamic Page Loading:**  
  - The webapp dynamically creates an `<iframe>` that re-loads the external page via the proxy script.
  - Every refresh is counted as a new visit, with the current progress displayed in the log.

## How It Works

1. **Proxy Handling:**  
   When accessing the script with the `proxy=1` parameter and a valid `url`, the PHP code:
   - Checks the URL's validity.
   - Uses cURL to retrieve the content from the target website.
   - Removes any problematic CSP directives embedded in the HTML.
   - Returns the sanitized content with the appropriate content type.

2. **Simulation Process:**  
   - The provided form collects three parameters: the target URL, the refresh interval (in seconds), and the number of simulated visits.
   - Once the simulation starts, an `<iframe>` is generated and loads the target URL through the proxy.
   - A JavaScript timer controls the refresh cycle based on user input and updates the log on each simulated visit.
   - Controls allow users to pause/resume or completely abort the process.

## Prerequisites

- **PHP:** Ensure your server is running an appropriate version of PHP.
- **cURL:** The PHP cURL extension must be enabled.
- **Internet Access:** The application leverages Bootstrap via a CDN for styling and JavaScript functionalities.

## Installation

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/bocaletto-luca/your-repository-name.git
   cd your-repository-name
   ```

2. **Server Configuration:**

   - Upload the files to your PHP-enabled web server.
   - Ensure that the cURL extension is active in your PHP configuration.

3. **Access the Application:**

   Open your browser and navigate to the hosted file (e.g., `http://your-domain.com/path/to/index.php`).

## Usage

1. **Enter the Target URL:**  
   Provide the URL that you wish to simulate visits for (for example, `https://example.com`).

2. **Set the Refresh Interval:**  
   Input the interval in seconds between each reload.

3. **Define the Number of Visits:**  
   Specify the total number of simulated visits.

4. **Control the Simulation:**  
   - Click **Start** to begin the simulation process.
   - Use **Pause** to temporarily halt the refresh cycle. The button will toggle to **Resume** when paused.
   - Click **Stop** to completely terminate the simulation and remove the generated `<iframe>`.

## Code Structure

- **PHP Proxy Section:**  
  Located at the beginning of the script, this section handles the proxy logic. It validates the URL, performs the cURL request to fetch the target page, strips out unnecessary CSP meta tags, and serves the content with the correct headers.

- **HTML & CSS Interface:**  
  The frontend is built using Bootstrap. Custom CSS is included to style the logging area and the container that holds the dynamically created `<iframe>`.

- **JavaScript Logic:**  
  JavaScript handles the simulation by:
  - Capturing user input from the form.
  - Dynamically creating and controlling the `<iframe>` element.
  - Logging the progress of each visit and managing the pause/resume and stop functionalities.

## Contributing

Contributions are welcome! If you have any suggestions or would like to enhance the functionality of the simulator, please feel free to open an issue or submit a pull request via GitHub.

## Disclaimer

- **Responsible Use:**  
  This webapp is intended for testing, demonstration, and educational purposes only. Misuse of this tool to generate unauthorized traffic can violate the terms of service of external websites. Please ensure that you use this tool responsibly and in accordance with applicable laws and regulations.

---
