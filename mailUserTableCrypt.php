<?php

declare( strict_types=1 );

/** Dovecot uses SHA-512 encryption. The passwords in the user table in the mail database need this encryption.
 * Use single quotes around the password when running this file with php.
 */
if ( count( $argv ) < 2 ) {
	echo "provide the password";
	exit(0);
}

$password = $argv[1];

echo crypt( $password, '$6$' );