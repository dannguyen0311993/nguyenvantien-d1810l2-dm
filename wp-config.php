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
/** The name of the database for WordPress */
define( 'DB_NAME', 'dan123' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'FQ|y9@#qh2~cUO>*_rjy97O+7hC8j(S6RoR7US!BX;IUTVU%pJN[/Z3c)~WMy=#c' );
define( 'SECURE_AUTH_KEY',  'YYixXd?&QKiLM.{;oV~D1&TYv3$^a&pf$gY[JDHs+R~)T@8r<|TX#/ru//V=#>qs' );
define( 'LOGGED_IN_KEY',    '70Am.QO;Cw;2@v,XVKGHcYEEllxwh==)%dx/ 99|R)-&9Ib-)kH@xZ=mQaQk/LFG' );
define( 'NONCE_KEY',        ';i->~WzB]YZtO ^$T*)7T>Kbb2MEq8QC5=jqZ%fUtXL^>0ly75KLX}3lUDO^0y4/' );
define( 'AUTH_SALT',        'TQcoLp6o{7[d ERp86B720fmY6Sw|d6Zz@Cb:}$,Dw`~S@[F&uRYdFC7:fNIawk@' );
define( 'SECURE_AUTH_SALT', 'p~zIj0y6:TY?Q54nr)OiwDlRv%^> w^B1MjnTl`#GN^cO6|M+K7sRdo(^rhS)yh~' );
define( 'LOGGED_IN_SALT',   '=R2W=$kb1gDcrl;OdatNh9f+iKO0C$e}xCgk;m $#+c!lknf!RN)g.j,p7,L3{WP' );
define( 'NONCE_SALT',       'ouX}yr>K6@MH%NAMZ0w_L7LoCJ??u#O}HF=j(B^7qG;:`&kjOtl]CvjJFy3_:iQR' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
