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
@@define('DB_NAME', 'lmre42038683583');

/** MySQL database username */
@@define('DB_USER', 'lmre42038683583');

/** MySQL database password */
@@define('DB_PASSWORD', 'ie}.!iM@3h4n');

/** MySQL hostname */
@@define('DB_HOST', '45.40.156.38:3311');

/** Database Charset to use in creating database tables. */
@@define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
@@define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'UBMmOrXMkLBj6c$mIm-f');
define('SECURE_AUTH_KEY',  'ZX)1I@&cPHwM55+#FJ3K');
define('LOGGED_IN_KEY',    'J)# 1C5wh=QH4(QjnA1z');
define('NONCE_KEY',        '6v GM3s@6xs%gyYNc4Mr');
define('AUTH_SALT',        'F(4q%Kw)O2rD!#+E/Z3M');
define('SECURE_AUTH_SALT', 'xLt_LL5NY_2pG/gzBd!g');
define('LOGGED_IN_SALT',   '+L_CI=m-T4R8Zb1II&Qt');
define('NONCE_SALT',       'X&wQkFs!Ov/YTbBgQ_Ob');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_cfp0k9tqm5_';

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
define('WP_DEBUG', false);
//define( 'WP_CACHE', true );
require_once( dirname( __FILE__ ) . '/gd-config.php' );
define( 'FS_METHOD', 'direct');
define('FS_CHMOD_DIR', (0705 & ~ umask()));
define('FS_CHMOD_FILE', (0604 & ~ umask()));


define( 'FORCE_SSL_ADMIN', true );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');