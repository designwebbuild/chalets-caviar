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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         's1sX89XcFe2btRPsK/VDZtijMf2aF1PaYuqm5MPHMnMQuMm8KEhptQpdeoFnQBqFcSIlrjXZO3R9NIg+Dm6dQQ==');
define('SECURE_AUTH_KEY',  'Q5Ag8/VhOA7kwCdHLNvUa5KMw6YjTexoLLDuJErpLChfZXtd9anfnjQLcECw7ody9tKN7zpUGB/uQf1FdWR5vw==');
define('LOGGED_IN_KEY',    '+llv/pM701JAyOPvO9uvKgQi+P/vHcKhL75VEpziGpB2FX1HdrCzQu0scrnxgg6YsrpmoIzzdqcuvnQT89clhA==');
define('NONCE_KEY',        'fsO7iQLNqGA9F67zwnbyGjSrdRVzI5Mx02QDxXtWJZ2wQwmLni354xZmzpXlR1JcB8eUgCMhBJEnLyuX0yOkaw==');
define('AUTH_SALT',        'y8pBp9G/K13OnWGR8CwEgeaZ+At854X4JWC9f0h/gtKkSz1sgKbjjwT81Wk7rABSk61+SpRsgD3PZfH63seoQw==');
define('SECURE_AUTH_SALT', 'fFGnMHSVYE2HihHRHG9cm1WOyXLqI2nCACWixQaRP5spDim40zZoeHbxMRAWDzPmDikY0Sm9kJ7bSk+a5O/DBw==');
define('LOGGED_IN_SALT',   'ZDHn1BGrYFHLcibQVqracvvoUwo5uJl1hApmO8Ulq2mbteNpnjKcT/ej9tbkAVcZxOMDP8xd66VeUzxJ6r7frQ==');
define('NONCE_SALT',       'UgwN1b4wkDgBVwsrHOQ6KlCQK7+5PTW5GNioMJM6LO1PCj74oZHNVbBQQ8RvSvxWts/eYyyhmyfV0qE0bIWk/g==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
