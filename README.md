# Picqer Amazon importer
With this script, you can (periodically) check for new orders in your Amazon Seller Central, and import those new orders into Picqer.

You can also use this script as a base to import orders from other marketplaces if you want.

Feel free to use and modify this script.

## Usage
- Do a composer install to get all the dependencies.
- Copy config-dist.php to config.php and set your own settings
- Run app.php to see if script works and imports your orders
- Optionally set a cronjob to run app.php every hour for automatic importing
