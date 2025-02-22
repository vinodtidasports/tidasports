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
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tidasports_wpsite' );
/** Database username */
define( 'DB_USER', 'tidasports' );
/** Database password */
define( 'DB_PASSWORD', 'g9pzZ;S~M)#Uw!sCtD' );
/** Database hostname */
define( 'DB_HOST', 'database-1.c3cmesuq8oze.ap-south-1.rds.amazonaws.com' );
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
 
define( 'AUTH_KEY',         'pYq^E#vq$!!>?xp7XEL7~AJ9q%fWXkNYE`z!XE,#x.n=Lwf)SH>4@/u~Z<,q(I+<' );
define( 'SECURE_AUTH_KEY',  '>-Tt!@@i(h00(wD-},A9u=^*eVtKNRoB~r,pJ}eb);anJcj`pFLJDRY.Z>U*Jz{<' );
define( 'LOGGED_IN_KEY',    'wU0GQ1iaf :s6AO9{AcSSF7[B8o atmsB^7at<>QlV8jNiS^/fyH|*J.5Ba~10X{' );
define( 'NONCE_KEY',        'O y8#C+6jSsEU|d/kLn$u>yi?-P;YVH0!kA$Z[aZ:-s<!B!JppQHs@^QHxP^nS5<' );
define( 'AUTH_SALT',        'tqmJSE1Gtw^q,s9tS^%M<OE7_L-^/2VEs970=h/d}IbGtN KVZjF#Bt5zWd~at`X' );
define( 'SECURE_AUTH_SALT', '_e@UIk<lyF-ds!|,:&l>ogu/Gn^?]c- 6~/8N3/j>7fLt}<W1|>N-`T~fg|AjVae' );
define( 'LOGGED_IN_SALT',   '*[0HLcwevTZgH7SIFNbSiN^&yUY:~~IO=QNZi@c#xSPpnNe>MQPbJ57ZI:t3weCm' );
define( 'NONCE_SALT',       ',5bq{K*{wtikA}-=DBn+3?bR.wvu}GQ&nT/)!t=XHQ-$[eWn/3{KAH(j`%&&|tU!' );
define('JWT_AUTH_SECRET_KEY', '5DjZ0N:E-bVIqT-Y6s_s:<Po$l]o0F8JyK4)1^|=5.,o>U#T=}|;S4WInnl{mRJ<');
define('JWT_AUTH_CORS_ENABLE', true);

define('FS_METHOD', 'direct');
define('FTP_PUBKEY', '/home/ubuntu/.ssh/id_rsa.pub');
define('FTP_PRIKEY', '/home/ubuntu/.ssh/id_rsa');
define('FTP_USER', 'sftp_user');
define('FTP_PASS', ''); // Leave this blank



define('WP_MEMORY_LIMIT', '1024M');

/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
if (!defined('WP_DEBUG')) {
    define( 'WP_DEBUG', true );
}
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', false );
@ini_set('display_errors', 0);
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

// Disable all wordpress auto updates. 
define('AUTOMATIC_UPDATER_DISABLED', true);
define('WP_AUTO_UPDATE_CORE', false);
define('CLOUDINARY_CLOUD_NAME', 'dxvobpwzc');
define('CLOUDINARY_API_KEY', '285531831575646');
define('CLOUDINARY_API_SECRET', 'ULlUBDwTvHzN9_OnyKgVJmx7REw');

/* Add any custom values between this line and the "stop editing" line. */
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';