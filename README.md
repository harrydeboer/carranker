<p><img alt="chrome-wheels" src="https://carranker.com/img/HeaderChrome.jpg"></p>

<h1>First install local and testing</h1>

<h5>The steps below must be followed in numerical order</h3>

<ol>
<li>Ask site owner for reCAPTCHA key, reCAPTCHA secret and fixer api key.</li>
<li>Cp .env.example to .env file in base directory and fill in the blanks except APP_KEY. 
Do not use passwords with "" around them. 
REDIS_HOST and DB_HOST have to belong to the same subnet as the network in docker-compose.yml.</li>
<li>On Windows: Execute the lines of the file config/wslIncreaseVm.txt in Powershell.</li>
<li>Execute the command ’docker-compose build’</li>
<li>Execute the command ’docker-compose up -d’</li>
<li>Execute ’docker exec --user devuser -it carranker composer install’ in base directory.</li>
<li>Execute command ’docker exec -it carranker php artisan key:generate’</li>
<li>Run command ‘docker exec -it carranker php artisan migrate’ in base directory.</li>
<li>Import the .sql files in database/sql-files in the order: makes, models, trims, 
roles, pages, menus, menus_pages and profanities.</li>
<li>Run command ‘docker exec -it carranker php artisan get:fx-rate’ in base directory.</li>
<li>Run command ‘docker exec -it carranker php artisan index:cars’ in base directory.</li>
<li>Run command ‘docker exec -it carranker php artisan passport:install’ in base directory.</li>
<li>Assign user_id to test account in table oauth_clients for client with password_client is 1.</li>
<li>Install npm.</li>
<li>Enable SCSS and UglifyJS file watchers with node_modules/.bin binaries.</li>
<li>Execute ’unitTests.sh’ for unit tests.</li>
<li>Execute ’featureTests.sh’ for feature tests.</li>
<li>Execute ’duskTests.sh’ for browser tests.</li>
<li>Execute ‘./jmeter -n -t CarRanker.jmx’ with .jmx in apache-jmeter/bin for stress tests.</li>
</ol>

<h2>First install acceptance and production</h3>

<ol>
<li>Follow the same steps as for install local except the test and file watcher steps. 
Composer needs to be installed with options --no-dev --no-progress --prefer-dist.</li>
<li>Execute command ‘chmod 777 -R storage’ in base directory.</li>
<li>Execute command ‘git reset –-hard’ in base directory. </li>
<li>Enable cronjob with crontab -e: 0 * * * * (cd path/to/base directory && php artisan get:fx-rate) > /dev/null.</li>
<li>Enable cronjob with crontab -e: */5 * * * * (cd path/to/base directory && php artisan process:queue) > /dev/null.</li>
<li>Enable cronjob with crontab -e: 0 12 * * * (cd /var/www/carranker.com && php artisan send-mail:when-pending-reviews) > /dev/null.</li>
<li>Repeat all the steps for the acceptance site with allowance for certain ips only (and the server ip) in the main 
apache.conf file. Do not add a fx rate cronjob for acceptance.</li>
<li>In production: install postfix, dovecot, opendkim, opendmarc, postsrsd and spamassassin.</li>
<li>In production: add yourdevuser@carranker.com, info@carranker.com, noreply@carranker.com, postmaster@carranker.com and root@carranker.com 
in. yourdevuser@carranker.com and info@carranker.com to carranker@gmail.com.</li>
<li>An update in production or acceptance can be retrieved with the command ’./update.sh’</li>
<li>To revert the update execute ’./rollback.sh’.</li>
</ol>
