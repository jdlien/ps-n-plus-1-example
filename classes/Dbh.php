<?php


/**
 * The root class for data-access classes. A connect() method allows access to a specified database.
 * Configured to use the DB in UTC, so when retrieving times, they should be passed to a JS date
 * object so they can be shown to the user in their local timezone.
 */
class Dbh
{
    /**
     * Creates a connection to the database with the correct database user, password, port, and host.
     *
     * @return PDO
     */
    public function connect()
    {
        // Try to read the database configuration .ini file and show an error if it can not be found.
        // Set the appropriate database settings in the .db.ini file.
        try {
            $config = parse_ini_file('../.db.ini');
            if (!$config) {
                echo '<pre><code style="color:red;">.db.ini file could not be found.';
                echo '<br><br>Ensure the correct path is set in classes/Dbh.php</code></pre>';
                exit('Error reading database configuration');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit('Error reading database configuration.');
        }

        $dsn = "mysql:host=" . $config['DB_HOST'] . ";port=" . $config['DB_PORT'] . ";dbname=" . $config['DB_NAME'];

        // time offset value we can use for MySQL to keep PHP and MySQL time in sync
        date_default_timezone_set($config['TIME_ZONE'] ?? 'UTC');

        try {
            // The ATTR_INIT_COMMAND ensure the time zone is set to TIME_ZONE
            $pdo = new PDO(
                $dsn,
                $config['DB_USER'],
                $config['DB_PASSWORD'],
                array(
                    PDO::MYSQL_ATTR_SSL_CA => $config['DB_SSL_CA'] ?? null,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '" . date('P') . "'",
                    // Uncomment this if you have issues with SSL
                    // PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                ),
            );

            return $pdo;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
    }
}
