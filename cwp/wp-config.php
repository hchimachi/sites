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
define( 'AUTH_KEY',         '9# Kd=?<-+6371eUGc#dc-H^1Qo6@K+ S xmbsfS0x/GwHbBj!N6f(&7138hd]T8' );
define( 'SECURE_AUTH_KEY',  '@Fb@BHs:M q7f&e!P<yFRVG/#TuS$TK#c]AF;#EP1{q+fZEFx0EgsS~Cghoj*T35' );
define( 'LOGGED_IN_KEY',    'N# f@0I~Tcq6|:{t[Yv$6Vo!/&Bk96?u!DqCz&x:F{8KE3]pxZS>I2YO3zv]]kOF' );
define( 'NONCE_KEY',        ']i8*NRek<AQd_~ 7I@0#V^${YYP-Ss39(ko),cq=8)y ^7_Md$=WC<*obe.+i *{' );
define( 'AUTH_SALT',        'oS8q}5&_JiUjV,so$zv/J0ms5~4L8ndZDFXD=9tELT4Rr&|:Df|<bU)F|h1ImuXp' );
define( 'SECURE_AUTH_SALT', '~dkCZDq#*YzqR-}ceJ9u%Gm*kZ&tM_}*!7aGXBz./#]:9vVxmjmvcZd_1*V3A1M+' );
define( 'LOGGED_IN_SALT',   'OfL~BB1HHsF(4Q0@D7]Z-G|p1c1]bfZ<>_(7FSAwPhSlzL6h>T]<tQRg@3+N(-B}' );
define( 'NONCE_SALT',       'IFg;t;>CN-Kl!(K<NC*Ovx*MXv(~oxgI$Os?L-SG~.>V_Q$-3Ecs,aYk^239,m8=' );

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
