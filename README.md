# Download File Through FTP

> Easy-to-use PHP functions to download files through FTP.

## Usage

Copy the files from the *build* directory to your project.

**Example**

```
require_once 'downloadFileThroughFtp.php';

downloadFileThroughFtp(
    'anonymous',                   // user name
    '',                            // password
    'speedtest.tele2.net',         // host
    '1KB.zip',                     // remote path
    'path/to/your/local/directory' // local directory path
);
```

It’s good practice to avoid hard-coding your connection information in your 
production code. Instead, you can use a configuration file that your code reads.

**Example: using configuration**

*config.php*

```
define( 'FTP_USERNAME', 'username' );
define( 'FTP_PASSWORD', '*********' );
define( 'FTP_HOST', 'example.com' );
define( 'FTP_REMOTE_PATH', 'remote/path/to/file' );
define( 'LOCAL_DIRECTORY_PATH', 'path/to/local/directory' );
```

*your-script.php*

```
require_once 'config.php';
require_once 'downloadFileThroughFtp.php';

downloadFileThroughFtp(
    FTP_USERNAME,
    FTP_PASSWORD,
    FTP_HOST,
    FTP_REMOTE_PATH,
    FTP_LOCAL_DIRECTORY_PATH
);
```

## Building and Testing

You’ll need [Composer](http://getcomposer.org) to build and test.

### Installing Dependencies

```
php composer.phar install
```

### Run Tests

```
vendor/bin/phpunit src
```

The tests will download a 1 kB zip file. You can change the FTP settings 
in *src/config-test.php*. See [test FTP server](ftp://speedtest.tele2.net/) for 
other files you can download. Of course, you can, for example, also change the 
settings to test your own FTP server, or adjust to your liking.

### Build Project

```
vendor/bin/phing
```

## License

MIT © [Frederik Krautwald](https://github.com/Frikki)
