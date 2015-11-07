# vimeo-sharelink

Note, this was written in 2 hours. It is simply a proof of concept. These two pages (admin.php and view.php) allow you to 

# Installation
1. Copy config.json.example to config.json, and add your access token from your app settings page
2. Host the entire directory in your webserver, or test it with a local php server via php -S localhost:8000
3. Access the administrative panel via http://{your_host}/admin.php (or the local php server via http://localhost:8000/admin.php)
4. Select a video, enter your start and end time
5. Distribute the share link as you see fit, users should only be able to access that share link within the specified date range