<?php
/**
 * FTP Download PHP
 *
 * Copyright (c) 2016, Frederik Krautwald. All rights reserved.
 */

require_once 'config-test.php';
require_once 'downloadFileThroughFtp.php';

/**
 * @coversDefaultClass
 */
class DownloadFileThroughFtpTest
    extends PHPUnit_Framework_TestCase
{
    private static $baseName;

    private static $localDirectoryRealPath;

    private $username = FTP_USERNAME;

    private $password = FTP_PASSWORD;

    private $host = FTP_HOST;

    private $remotePath = FTP_REMOTE_PATH;

    private $localDirectoryPath = LOCAL_DIRECTORY_PATH;

    /**
     * @test
     */
    public function shouldDownloadBigBuyFile()
    {
        $localDirectory = realpath( dirname( __FILE__ ) )
            . DIRECTORY_SEPARATOR . $this->localDirectoryPath;

        self::$localDirectoryRealPath = $localDirectory;

        if ( !is_dir( $localDirectory ) )
            mkdir( $localDirectory );

        downloadFileThroughFtp(
            $this->username,
            $this->password,
            $this->host,
            $this->remotePath,
            $this->localDirectoryPath
        );

        self::$baseName = $localDirectory . DIRECTORY_SEPARATOR .
            basename( $this->remotePath );

        $isBigBuyFileDownloaded = file_exists( self::$baseName );

        $this->assertTrue(
            $isBigBuyFileDownloaded,
            'BigBuy-file was not downloaded.'
        );
    }

    public static function tearDownAfterClass()
    {
        if ( file_exists( self::$baseName ) )
        {
            unlink( self::$baseName );
            rmdir( self::$localDirectoryRealPath );
        }
    }
}
//
// EOF: DownloadFileThroughFtpTest.php
