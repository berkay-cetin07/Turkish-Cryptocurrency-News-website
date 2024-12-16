# How to Run the Website

## Running with docker-compose.yml
To run the project in any environment with minimal setup, ensure you have Docker and docker-compose installed. Once set up, navigate to the project root directory (where docker-compose.yml is located) and run:

```bash
docker-compose up --build
```

This will build the image and run the container. The website will be available at `http://localhost:8080`.

## Folder Structure
Below is the recommended folder structure that we’ve set up for the project. It’s organized to keep code, configuration, and assets separated for easy maintenance and collaboration:

```plaintext
project-root/
├─ docker/
│  ├─ Dockerfile            # Docker build instructions
│  └─ apache-php.conf       # Apache configuration if needed
│
├─ public/
│  ├─ index.php             # Main entry point of the website
│  ├─ css/
│  │  └─ style.css          # Main stylesheet
│  ├─ js/
│  │  └─ main.js            # Main JavaScript file
│  ├─ images/
│  │  └─ logo.png           # Example image file
│  └─ pages/
│     ├─ home.php           # Homepage
│     ├─ news.php           # News listing page
│     ├─ coin-values.php    # Coin values page
│     ├─ charts.php         # Chart display page
│     └─ search-results.php # Search results page
│
├─ src/
│  ├─ config/
│  │  └─ config.php         # Configuration constants (API keys, URLs)
│  ├─ includes/
│  │  ├─ header.php         # Shared header template
│  │  ├─ footer.php         # Shared footer template
│  │  ├─ navbar.php         # Shared navigation bar
│  │  ├─ functions.php      # Utility functions (fetching data, RSS parsing, etc.)
│  │  └─ api.php            # API wrapper classes or functions
│  └─ classes/
│     └─ CryptoAPI.php      # Example class to interact with crypto APIs
│
├─ composer.json             # For PHP dependencies if needed
└─ docker-compose.yml        # Docker-compose configuration
```


## How to add a new page
### Create the PHP file:
In public/pages/, create a new .php file for your page. For example, my-new-page.php.

### Add the page to the router:
In public/index.php, add the new page to the $allowed_pages array or the routing logic so that /?page=my-new-page can be accessed. For example:
```php
$allowed_pages = ['home', 'news', 'coin-values', 'search-results', 'charts', 'my-new-page'];
```
### Include Content
In my-new-page.php, write your HTML and PHP code. The header, navbar, and footer are automatically included if you follow the given structure.

And then, you can access the page at http://localhost:8080/?page=my-new-page.

## How to add new styles

### Global Styles:
Use the public/css/style.css file for global site-wide styles.

### Additional Stylesheets:
If you want to keep your styles organized, you can create more CSS files in public/css/. For example, public/css/news.css.

### Link the Stylesheet:
In src/includes/header.php, link the new CSS file:
```html
<link rel="stylesheet" href="css/news.css">
```