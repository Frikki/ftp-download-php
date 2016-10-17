<?php
/**
 * brightlife
 *
 * Copyright (c) 2016, Frederik Krautwald. All rights reserved.
 */

function downloadFileThroughFtp(
    $username,
    $password,
    $host,
    $absolutePath,
    $localDirectoryPath )
{
    $baseName = extractBaseNameFromPath( $absolutePath );

    $ftpStream = ftp_connect( $host );

    ftp_login( $ftpStream, $username, $password );
    ftp_pasv( $ftpStream, TRUE );

    $isFileDownloaded = writeLocalFileFromFtpStream(
        $baseName, $localDirectoryPath, $ftpStream, $absolutePath );

    ftp_close( $ftpStream );

    return $isFileDownloaded;
}

function extractBaseNameFromPath( $absolutePath )
{
    $explodedDirectoryName = explode( DIRECTORY_SEPARATOR, $absolutePath );
    $baseName = end( $explodedDirectoryName );

    return $baseName;
}

function writeLocalFileFromFtpStream(
    $baseName, $localDirectoryPath, $ftpStream, $absolutePath )
{
    $TMP_PREFIX = 'tmp_';
    $WRITE_ONLY_MODE = 'w';

    $realPathDirectory = realpath( dirname( __FILE__ ) )
        . DIRECTORY_SEPARATOR
        . $localDirectoryPath
        . DIRECTORY_SEPARATOR;

    $tmpBaseName = $realPathDirectory . $TMP_PREFIX . $baseName;

    $handle = fopen( $tmpBaseName, $WRITE_ONLY_MODE );
    $isFileWritten = ftp_fget( $ftpStream, $handle, $absolutePath, FTP_BINARY );
    fclose( $handle );

    if ( $isFileWritten )
        rename( $tmpBaseName, $realPathDirectory . $baseName );

    return $isFileWritten;
}
