<p align="center"><img src="https://carranker.com/img/HeaderChrome.jpg"></p>

<h1>First install acceptance and production</h1>

<h3>The steps below must be followed in numerical order</h3>

<ol>
<li>Create the carranker database.</li>
<li>Sudo mkdir sitefolder in /var/www. The sitefolder must have the same name as the site name (domain name) in /etc/apache2/sites-available (without the .conf).</li>
<li>Execute command ‘sudo chown {youruser}:{yourusergroup} {sitefolder}’ in /var/www. The owner of the sitefolder cannot be root.</li>
<li>Execute command ‘git init’ in sitefolder.</li>
<li>Execute command ‘git remote add origin {ssh-origin}’.</li>
<li>Execute command ‘git pull origin master’.</li>
<li>Make apache configuration files for laravel and wordpress and edit these files to match the sites.</li>
<li>Run the certbot command with the laravel and wordpress sites added.</li>
<li>Enable the new sites.</li>
<li>Enable the necessary apache modules ‘a2enmod headers proxy rewrite proxy_balancer proxy_http slotmem_shm ssl’.</li>
<li>Enable the php modules listed in composer.json.</li>
<li>Restart apache.</li>
<li>Ask site owner for recaptcha key, secret and fixer api key.</li>
<li>Cp .env.example to .env file in sitefolder folder and fill in database credentials etc.</li>
<li>Execute command php artisan key:generate</li>
<li>Execute ’composer install --no-dev --no-progress --prefer-dist’ in sitefolder.</li>
<li>Browse to the cms and install wordpress.</li>
<li>Execute command ‘chmod 777 -R storage’ in sitefolder.</li>
<li>Execute command ‘git reset –hard’ in sitefolder. </li>
<li>Run command ‘php artisan migrate’ in sitefolder.</li>
<li>Import the .sql files in database/sql-files in the order: makes, models, trims and profanities.</li>
<li>Add pages Home, Auth, Register, About, Contact and PHPinfo.</li>
<li>Add menus navigationHeader with page Home as primary menu and navigationFooter with About and Contact as footer menu.</li>
<li>Activate the Carranker Theme and set the permalinks on ’Post name’.</li>
<li>Activate the plugins.</li>
<li>Add permissions to the role editor: create_users, delete_users, edit_theme_options, edit_themes, edit_users, list_users and remove_users.</li>
<li>Enable cronjob with crontab -e: * * * * * (cd path/to/sitefolder && php artisan getcmsdata).</li>
<li>Enable cronjob with crontab -e: 0 * * * * (cd path/to/sitefolder && php artisan getfxrate).</li>
<li>Run command ‘php artisan getcmsdata’ in sitefolder.</li>
<li>Run command ‘php artisan getfxrate’ in sitefolder.</li>
<li>Run command ‘php artisan passport:install’ in sitefolder.</li>
<li>Assign user_id to test account in table oauth_clients for client with password_client is 1.</li>
<li>Install varnish for acceptance and production.</li>
<li>Install redis.</li>
<li>Repeat all the steps for the acceptance site with allowance for certain ips only (and the server ip) in the main apache.conf file. Do not add a fx rate cronjob for acceptance.</li>
<li>In production: install postfix, dovecot, opendkim, opendmarc, postsrsd and spamassassin.</li>
<li>In production: add harry@carranker.com, info@carranker.com, postmaster@carranker.com and root@carranker.com in Mail Users in the admin panel. Forward info@carranker.com to carranker@gmail.com.</li>
<li>An update in production or acceptance can be retrieved with the command ’./update.sh’</li>
</ol>


<h3>First install local and testing</h3>

<ol>
<li>Clone git repository in sitefolder.</li>
<li>Execute the command ’docker-compose build’</li>
<li>Execute the command ’docker-compose up -d’</li>
<li>Ask site owner for recaptcha key, secret and fixer api key.</li>
<li>Cp .env.example to .env file in sitefolder folder and fill in database credentials etc.</li>
<li>Execute command ’docker exec -it carranker php artisan key:generate’</li>
<li>Execute ’docker-compose exec --user devuser composer install’ in sitefolder.</li>
<li>Browse to the cms at http://cms.carranker:8080 and install wordpress.</li>
<li>Execute command ‘chmod 777 -R storage’ in container.</li>
<li>Execute command ‘git reset –hard’ in sitefolder. </li>
<li>Run command ‘php artisan migrate’ in sitefolder.</li>
<li>Import the .sql files in database/sql-files in the order: makes, models, trims and profanities.</li>
<li>Add pages Home, Auth, Register, About, Contact and PHPinfo.</li>
<li>Add menus navigationHeader with page Home as primary menu and navigationFooter with About and Contact as footer menu.</li>
<li>Activate the Carranker Theme and set the permalinks on ’Post name’.</li>
<li>Activate the plugins.</li>
<li>Add permissions to the role editor: create_users, delete_users, edit_theme_options, edit_themes, edit_users, list_users and remove_users.</li>
<li>Run command ‘php artisan getcmsdata’ in sitefolder.</li>
<li>Run command ‘php artisan getfxrate’ in sitefolder.</li>
<li>Run command ‘php artisan passport:install’ in sitefolder.</li>
<li>Assign user_id to test account in table oauth_clients for client with password_client is 1.</li>
<li>Run ‘php artisan dusk:install’ when dusk is used the first time.</li>
<li>Execute ’./unittests.sh’ for unit tests.</li>
<li>Execute ’./featuretests.sh’ for feature tests.</li>
<li>Execute ’./wordpresstests.sh’ for feature tests.</li>
<li>Execute ’docker exec -it carranker php artisan dusk’ for browser tests.</li>
<li>Execute ‘./jmeter -n -t CarRanker.jmx’ with .jmx in apache-jmeter/bin for stress tests.</li>
</ol>