<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //

if (strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')) { /** Conditional logic to find local server */
	/** The name of the database for WordPress */
	define( 'DB_NAME', 'local' );

	/** MySQL database username */
	define( 'DB_USER', 'root' );

	/** MySQL database password */
	define( 'DB_PASSWORD', 'root' );

	/** MySQL hostname */
	define( 'DB_HOST', 'localhost' ); 
} else { /** used for live to point the server to proper database when migrating  */

	define( 'DB_NAME', 'local' ); /** Database name found in MySQL on host providers */

	define( 'DB_USER', 'root' ); /** User name found in MySQL . ___________ . */

	define( 'DB_PASSWORD', 'root' ); /** Password of the user name that has access to the database */

	define( 'DB_HOST', 'localhost' ); /** usually 127.0.0.1 but some host providers use different so you have to contact them */

}



/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '1eSmNhqdoQdC6qDTWAMPK7zaHOtAcZdxnfOxHzdQyQpBhBN8m4vdGfRImF6GezNa5tqT5JAsWKKzc1ePmOK0Mg==');
define('SECURE_AUTH_KEY',  'a7KLsUs3g4odSfM3Mh13XgjmHbNJ/Mrnjv9MLMeAtghfraCU8Mi+vxfxLFsP1e5OG2Gb2SxTkoiiJF+JNzVIDA==');
define('LOGGED_IN_KEY',    'EgqFY+tNVdeEI0nPm7jyqqN4QOBCXvLw9H4fUXKAK/kW26oQy1QWxeUWsIhiQPnTdCsfQzl85J8AYHQxi7AFEg==');
define('NONCE_KEY',        'gWZw/4jd4RA1lq3EPQsStNsQz7Bj40e+ErJrNIZ2Jb6mpNvjlqQ7mls+pf9adyTv2VeRcMOcMY/UnshYMLk5ag==');
define('AUTH_SALT',        'NX6SDvdR2JcLZbIa7pfH61MP0TLNRnC8NDgJ7fP/symmQWARVAH8rjjs9Yi2zAfdnPbj9Cy9iAcLyUsgxz9ZEg==');
define('SECURE_AUTH_SALT', '4D/XrgAA6XXWz75t7G32OXby/MeDBPA4OE/eJaETH0ilogKEJgcScLvcEHfc6/D8OklrIgPObbw/4/J6Y+v5IQ==');
define('LOGGED_IN_SALT',   'X9pn+pE0B7Jb+MIvHQmskrEAk1IhA1N2AKnc70O1VUXA+EQmUx0nWqUJMyLXEq+kgBDUHaj9+aPULh5lINEAkg==');
define('NONCE_SALT',       'NhThZXvhEe6akQbnXOUgBOHyHOab9+C9Ws1qHIWs5wgyVd3xsHbSIO51bM8l8+8cFYVdP3Y1h3RQ5dn4flF1Ig==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
