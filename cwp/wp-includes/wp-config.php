<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'cwp' );

/** MySQL database username */
define( 'DB_USER', 'admin' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Denchizik-2018' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         '36MSp~2`IfwkxVcFFQ2]]YRD],avh]Qd>1!6KogHw!0LZ;R}(cQ.Ox3^tuM[)x/X' );
define( 'SECURE_AUTH_KEY',  '_%]@DkC/,o)se0=H`0uABYH&/(r@QyfRGcD]SjpA%a0bx 7F^uPEncnr:==3/jHC' );
define( 'LOGGED_IN_KEY',    ')5~<q%Rz[3Gl7Oo5QWD<gjsY%Nvc+{Q@E;m4/~az{-CIM?U5wH}g]twyCn6+.6:H' );
define( 'NONCE_KEY',        'G:6L0c]+[:e;ZhIH-/JDqPx^!B.B^Jg!o|6zH2^(lO1rEuWKsBkM{n,]PTn8]%Q7' );
define( 'AUTH_SALT',        'Y)-#b+O$#d8oFZ3jKqPb(13#&4H13I3;n2^/?-E*2 .lK<plX_RmwB2*PeNk)S)b' );
define( 'SECURE_AUTH_SALT', '^Glc1O<4N%ch.UO9NIF66oy^t3@TXzpH3-(bym`7eWRV}k+eGpAv?vzD>|RCoe@j' );
define( 'LOGGED_IN_SALT',   'MP)~4bcHy}O(2$gipRy;i}^ll:i0#PhKj8 dUMZunKx]#6X[DFbrE(y3&!84 Wfm' );
define( 'NONCE_SALT',       '-&:9BCU:oxk!}vw!/;Qfh5?rovQO;o?/a?g#;Z].X6OEDX471s(K,F~R}^U[p90-' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
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
