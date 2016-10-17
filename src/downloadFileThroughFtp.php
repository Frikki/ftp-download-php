<?php
/**
 * FTP Download PHP
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

    $targetPath = computeAbsoluteLocalDirectoryPath( $localDirectoryPath );

    $tmpBaseName = $targetPath . $TMP_PREFIX . $baseName;

    $handle = fopen( $tmpBaseName, $WRITE_ONLY_MODE );
    $isFileWritten = ftp_fget( $ftpStream, $handle, $absolutePath, FTP_BINARY );
    fclose( $handle );

    if ( $isFileWritten )
        rename( $tmpBaseName, $targetPath . $baseName );

    return $isFileWritten;
}

function computeAbsoluteLocalDirectoryPath( $localDirectoryPath ) {
    $PARENT_DIRECTORY_DELIMITER = '../';
    $RE_PARENT_DIRECTORY = "/\.\.\//";

    preg_match_all( $RE_PARENT_DIRECTORY, $localDirectoryPath, $matches );

    $directoriesUp = rtrim(
        DIRECTORY_SEPARATOR . implode( "", $matches[ 0 ] ),
        DIRECTORY_SEPARATOR );
    $currentDirectory = dirname( __FILE__ );
    $absolutParent = realpath( $currentDirectory . $directoriesUp );
    $knownLocalPath = array_pop(
        explode( $PARENT_DIRECTORY_DELIMITER, $localDirectoryPath ) );
    $targetPath = $absolutParent .
        DIRECTORY_SEPARATOR .
        $knownLocalPath .
        DIRECTORY_SEPARATOR;

    return $targetPath;
}
