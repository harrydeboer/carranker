<?php

declare( strict_types=1 );

if ( count( $argv ) < 2 ) {
	echo "provide the password";
	exit(0);
}

$password = $argv[1];

echo crypt( $password, '$6$' ), "\n";