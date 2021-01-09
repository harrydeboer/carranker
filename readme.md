<p align="center"><img src="https://carranker.com/img/HeaderChrome.jpg"></p>

<h1>First install local and testing</h1>

<h5>The steps below must be followed in numerical order</h3>

<ol>
<li>When using Windows: use Powershell for the docker commands and Git Bash for everything else. 
Or prepend Git Bash docker commands with winpty .</li>
<li>Clone git repository in sitefolder.</li>
<li>Ask site owner for reCAPTCHA key, reCAPTCHA secret and fixer api key.</li>
<li>Cp .env.example to .env file in sitefolder folder and fill in the blanks except APP_KEY. 
Do not use passwords with "" around them. 
REDIS_HOST and DB_HOST have to belong to the same subnet as the network in docker-compose.yml.</li>
<li>On Windows: Place the config/.wslconfig file in the user folder</li>
<li>On Windows: Execute the lines of the file wslincreasevm in Powershell.</li>
<li>Execute the command ’docker-compose build’</li>
<li>Execute the command ’docker-compose up -d’</li>
<li>Execute ’docker exec --user devuser -it carranker composer install’ in sitefolder.</li>
<li>Execute command ’docker exec -it carranker php artisan key:generate’</li>
<li>Browse to the cms at http://cms.carranker:8080 and install wordpress.</li>
<li>Run command ‘docker exec -it carranker php artisan migrate’ in sitefolder.</li>
<li>Import the .sql files in database/sql-files in the order: makes, models, trims and profanities.</li>
<li>Run command ‘docker exec -it carranker php artisan getcmsdata’ in sitefolder.</li>
<li>Run command ‘docker exec -it carranker php artisan getfxrate’ in sitefolder.</li>
<li>Run command ‘docker exec -it carranker php artisan indexcars’ in sitefolder.</li>
<li>Run command ‘docker exec -it carranker php artisan passport:install’ in sitefolder.</li>
<li>Use phploc and phpcpd phars to count the lines of code and detect copy pasts in the code.</li>
<li>Assign user_id to test account in table oauth_clients for client with password_client is 1.</li>
<li>Execute npm install.</li>
<li>Enable scss and uglifyjs filewatchers with node_modules/.bin binaries.</li>
<li>Execute ’bin/unittests.sh’ for unit tests.</li>
<li>Execute ’bin/featuretests.sh’ for feature tests.</li>
<li>Execute ’bin/dusktests.sh’ for browser tests.</li>
<li>Execute ‘./jmeter -n -t CarRanker.jmx’ with .jmx in apache-jmeter/bin for stress tests.</li>
</ol>

<h2>First install acceptance and production</h3>

<ol>
<li>Create the carranker database.</li>
<li>Sudo mkdir sitefolder in /var/www. The sitefolder must have the same name as the site name (domain name) in 
/etc/apache2/sites-available (without the .conf).</li>
<li>Execute command ‘sudo chown {youruser}:{yourusergroup} {sitefolder}’ in /var/www. The owner of the sitefolder 
cannot be root.</li>
<li>Execute command ‘git init’ in sitefolder.</li>
<li>Execute command ‘git remote add origin {ssh-origin}’.</li>
<li>Execute command ‘git pull origin master’.</li>
<li>Make apache configuration files for laravel and edit these files to match the sites.</li>
<li>Run the certbot command with the laravel site added.</li>
<li>Enable the new sites.</li>
<li>Enable the necessary apache modules ‘a2enmod headers proxy rewrite proxy_balancer proxy_http slotmem_shm ssl’.</li>
<li>Enable the php modules listed in composer.json.</li>
<li>Restart apache.</li>
<li>Ask site owner for reCAPTCHA key, secret and fixer api key.</li>
<li>Cp .env.example to .env file in sitefolder folder and fill in database credentials etc.</li>
<li>Execute command php artisan key:generate</li>
<li>Execute ’composer install --no-dev --no-progress --prefer-dist’ in sitefolder.</li>
<li>Browse to the cms and install wordpress.</li>
<li>Execute command ‘chmod 777 -R storage’ in sitefolder.</li>
<li>Execute command ‘git reset –hard’ in sitefolder. </li>
<li>Run command ‘php artisan migrate’ in sitefolder.</li>
<li>Import the .sql files in database/sql-files in the order: makes, models, trims and profanities.</li>
<li>Enable cronjob with crontab -e: 0 * * * * (cd path/to/sitefolder && php artisan getfxrate).</li>
<li>Enable cronjob with crontab -e: */5 * * * * (cd path/to/sitefolder && php artisan processqueue).</li>
<li>Run command ‘php artisan getcmsdata’ in sitefolder.</li>
<li>Run command ‘php artisan getfxrate’ in sitefolder.</li>
<li>Install elasticsearch (version number can be found in docker-compose).</li>
<li>Run command ‘php artisan indexcars’ in sitefolder.</li>
<li>Run command ‘php artisan passport:install’ in sitefolder.</li>
<li>Assign user_id to test account in table oauth_clients for client with password_client is 1.</li>
<li>Install varnish for acceptance and production.</li>
<li>Install redis.</li>
<li>Repeat all the steps for the acceptance site with allowance for certain ips only (and the server ip) in the main 
apache.conf file. Do not add a fx rate cronjob for acceptance.</li>
<li>In production: install postfix, dovecot, opendkim, opendmarc, postsrsd and spamassassin.</li>
<li>In production: add <yourdevuser>@carranker.com, info@carranker.com, noreply@carranker.com, postmaster@carranker.com and root@carranker.com 
in. <yourdevuser>@carranker.com and info@carranker.com to carranker@gmail.com.</li>
<li>An update in production or acceptance can be retrieved inside the bin folder with the command ’./update.sh’</li>
<li>To revert the update go to the bin folder and execute ’./rollback.sh’.</li>
</ol>
