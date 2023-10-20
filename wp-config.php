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
define( 'DB_NAME', 'linktest' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
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
define( 'AUTH_KEY',         'oG{]6^SF;{*BuksIHbJ~=U)8WZ:+5Hlr}b;sxb$2WZqjm*ha0yg&:S0z&^V<~g#u' );
define( 'SECURE_AUTH_KEY',  'M_S^2|754[Je]0VrUu,H|4P/+!GMYLa4G(mqoN096L6&BLqgQ!)up1Itp+e$r#hj' );
define( 'LOGGED_IN_KEY',    '7?~l_?vJj7j@B<,<|1x,dtva$LS36<k``IIRvT(]A6zfUA]$=vptnz$u?&yB%C)9' );
define( 'NONCE_KEY',        '=;vl6rfS.@QdBjusS1CZOE,Ke~rRXIt8NF!x1}dYu&8l+JM*Jnt% IU3tW(w!Wa]' );
define( 'AUTH_SALT',        'BfQ`u%gwXM5GTw5&`I/`|13aZq)KcVrxXqpr#T*9A+y0Jd)7yOP$b}V7y=&]cs-J' );
define( 'SECURE_AUTH_SALT', 'JP27#*IJ2z+bPe3<s)24hTdiN%;]mBNBRB8Vqz7[r&`ug[k5%7xg8w3Buv%PmO!u' );
define( 'LOGGED_IN_SALT',   'C-:dY]W>>&4_s:!^Adl/LdV)mf?ug-#Q[^1:DS5~5. q^=q*K vFFXYS+38gBocP' );
define( 'NONCE_SALT',       'Bk:Z|7/7^XlomcKL9BBT%<wqh$./j)^|UAsC,nvE[bH4$7.-r^QXU LVk2y$HBPo' );

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
