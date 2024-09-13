<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'my-woocommerce-site' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'FSo9GJFjfzipTvrDaGwzWzsYar7WE6AtUynE9NP4NdmRptbRClGCxgL4XMwmRfkh' );
define( 'SECURE_AUTH_KEY',  'qvb18OTde9ncbOUgG2NdRJr8f4c3xsQ2U6W1tTdcOuDm43gRzmCjz6bD3LEfB7xd' );
define( 'LOGGED_IN_KEY',    '36XlaNRBs64gad5yiFldLATSYgfa6AEFc28HXVWwr0ublaEcL5b75rF7SRayMon7' );
define( 'NONCE_KEY',        'yIEIMxh6f47CtYZWY7VWUMqswrW7LBOkr2cnUcgm9cryJus19pyF2i9QPtDLQDqd' );
define( 'AUTH_SALT',        'zJFIh39pixADpfCfI5MeMS3wrx6lJPm1JRzluC2R2O5EsYlpwHOeTaGWpHGcQJnA' );
define( 'SECURE_AUTH_SALT', 'HMCbJbv6dZPvjapodhZuLygxbqzRy0h5uH5mUUxAZWVJLfU1JJLqHJkqz3phR8RX' );
define( 'LOGGED_IN_SALT',   'FGSic7FJS06om12ptsrS2Rxh7C9t7tQua7yOlw7HXgaWZH6ZfvteVux0we3qrTqa' );
define( 'NONCE_SALT',       'SunoZXGjkVfA6neKIwaxGdBNRv8cQbOpaYvTROmo7YpJNrtvlAeMUZD3T8FEL1p1' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
