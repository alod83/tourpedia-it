<?php

/**
 * Contains class SevenZipArchive
 * @package SevenZipArchive
 */

/*
 * Copyright 2012 Max Schuster 
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace eu\maxschuster\sevenzip;

use eu\maxschuster\sevenzip\cmd\ListArchive;
use eu\maxschuster\sevenzip\cmd\TestArchive;
use eu\maxschuster\sevenzip\exception\SevenZipException;
use eu\maxschuster\sevenzip\result\SevenZipItem;
use eu\maxschuster\sevenzip\cmd\SevenZipCommand;
use eu\maxschuster\sevenzip\cmd\CreateEmptyArchive;
use eu\maxschuster\sevenzip\cmd\AddFile;
use eu\maxschuster\sevenzip\cmd\AddFileFromString;
use eu\maxschuster\sevenzip\cmd\GetFromName;
use eu\maxschuster\sevenzip\cmd\ExtractFiles;
use eu\maxschuster\sevenzip\cmd\Rename;

/**
 * Class to access 7-zip achives using the commandline version of 7-zip
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
class SevenZipArchive {

    /**
     * Path to the 7zip executable file
     * @var string
     */
    protected $executableFile;

    /**
     * Name of the currently open zip file
     * @var string
     */
    protected $archivePath;

    /**
     * Password
     * @var string
     */
    protected $password;

    /**
     * Encrypt archive headers,
     * so filenames will be encrypted.
     * @var bool
     */
    protected $encryptArchiveHeaders;
    
    /**
     * Enable the double quote fix for proc_open on WINNT mashines
     * @var boolean
     */
    public static $winDoubleQuoteFix = true;

    /**
     * Constructor of SevenZipArchive
     * @param string $executableFile Path to the 7z executable
     */
    public function __construct($executableFile = '7z') {
        $this->setExecutableFile($executableFile);
    }

    /**
     * Opens an existing 7zip archive or creates a new one
     * @param string $filename Path to the 7zip file
     * @param string $password Password
     * @throws SevenZipException
     */
    public function open($filename, $password = '') {
        $this->setPassword($password);
        $delLater = false;

        if (!is_file($filename)) {
            touch($filename);
            $delLater = true;
        }

        $newPath = realpath($filename);

        if ($delLater) {
            unlink($newPath);
            $createCmd = new CreateEmptyArchive($this->getExecutableFile());
            $createCmd->setBaseArchiveFile($newPath);
            $createCmd->setPassword($this->getPassword());
            $createCmd->execute();
        }

        if (!$this->testArchive($newPath)) {
            throw new SevenZipException('The given archvie is broken!');
        }

        if (is_writable($newPath)) {
            $this->setArchivePath($newPath);
            return true;
        }
        throw new SevenZipException('The given archvie is not writable!(\'' . $newPath . '\')');
    }

    /**
     * Closes the archive (not needed...)
     */
    public function close() {
        $this->archivePath = null;
    }

    /**
     * Checks if an archive is open.
     * @return boolean Has an open archive
     * @throws SevenZipException
     */
    protected function checkIfOpen() {
        if ($this->archivePath) {
            return true;
        }
        throw new SevenZipException('No open archive!');
    }

    /**
     * Checks if an existing archive is ok
     * @param string $path Path of archive
     * @return boolean Is ok?
     */
    protected function testArchive($path) {
        $testCmd = new TestArchive($this->getExecutableFile());
        $testCmd->setBaseArchiveFile($path);
        $testCmd->setPassword($this->getPassword());
        $testCmd->setEncryptArchiveHeaders($this->getEncryptArchiveHeaders());
        $testCmd->execute();
        return $testCmd->getLastResult();
    }

    /**
     * Lists the content of the given archive
     * @return SevenZipItem[] Array of SevenZipFiles and SevenZipFolders
     */
    public function listArchive() {
        $this->checkIfOpen();
        $listCmd = new ListArchive($this->getExecutableFile());
        $listCmd->setBaseArchiveFile($this->getArchivePath());
        $listCmd->setEncryptArchiveHeaders($this->getEncryptArchiveHeaders());
        $listCmd->execute();
        return $listCmd->getLastResult();
    }

    /**
     * Adds a file to the archive
     * @param string $filename Path of the file inside the filesystem 
     * @param string $localname [optional] Path inside the archive
     * @return boolean True if successfull
     */
    public function addFile($filename, $localname = NULL) {
        $this->checkIfOpen();
        $addFileCmd = new AddFile($this->getExecutableFile());
        $addFileCmd->setBaseArchiveFile($this->getArchivePath());
        $addFileCmd->setFilename($filename);
        $addFileCmd->setLocalname($localname);
        $addFileCmd->setPassword($this->getPassword());
        $addFileCmd->setEncryptArchiveHeaders($this->getEncryptArchiveHeaders());
        $addFileCmd->execute();
        return $addFileCmd->getLastResult();
    }

    /**
     * Creates a file inside the archive with the given contents
     * @param string $localname Path inside the archive
     * @param string $contents Filecontents
     * @return boolean True if successfull
     */
    public function addFileFromString($localname, $contents) {
        $this->checkIfOpen();
        $cmd = new AddFileFromString($this->getExecutableFile());
        $cmd->setPassword($this->getPassword());
        $cmd->setEncryptArchiveHeaders($this->getEncryptArchiveHeaders());
        $cmd->setLocalname($localname);
        $cmd->setContents($contents);
        $cmd->setBaseArchiveFile($this->getArchivePath());
        $cmd->execute();
        return $cmd->getLastResult();
    }

    /**
     * Gets the contents of the given filename from the archive
     * @param string $name Name of the file inside the archive
     * @return string Contents
     */
    public function getFromName($name) {
        $this->checkIfOpen();
        $cmd = new GetFromName($this->getExecutableFile());
        $cmd->setBaseArchiveFile($this->getArchivePath());
        $cmd->setName($name);
        $cmd->setPassword($this->getPassword());
        $cmd->setEncryptArchiveHeaders($this->getEncryptArchiveHeaders());
        $cmd->execute();
        return $cmd->getLastOutput();
    }

    /**
     * Extracts files from the archive
     * @param string $destination Path where the archive contents should get extracted
     * @param string|array $entries [optional]
     * One filename as string or an array of filenames that should be extracted
     * @return boolean True if successfull
     */
    public function extractTo($destination, $entries = null) {
        $this->checkIfOpen();
        if (is_string($entries)) {
            $entries = array($entries);
        }
        $cmd = new ExtractFiles($this->getExecutableFile());
        $cmd->setBaseArchiveFile($this->getArchivePath());
        $cmd->setDestination(realpath($destination));
        if (is_array($entries)) {
            $cmd->setEntries($entries);
        }
        $cmd->setPassword($this->getPassword());
        $cmd->setEncryptArchiveHeaders($this->getEncryptArchiveHeaders());
        $cmd->execute();
        return $cmd->getLastResult();
    }

    /**
     * Renames a file inside the archive
     * IMPORTANT:
     * Only available after 7-Zip 9.25 alpha
     * (http://sourceforge.net/projects/sevenzip/forums/forum/45797/topic/5367077)
     * @param string $name Old filename
     * @param string $newname New filena
     * @return boolean True if successfull
     */
    public function renameName($name, $newname) {
        $this->checkIfOpen();
        $cmd = new Rename($this->getExecutableFile());
        $cmd->setPassword($this->getPassword());
        $cmd->setEncryptArchiveHeaders($this->getEncryptArchiveHeaders());
        $cmd->setBaseArchiveFile($this->getArchivePath());
        $cmd->setName($name);
        $cmd->setNewname($newname);
        $cmd->execute();
        return $cmd->getLastResult();
    }

    // GETTER SETTER

    /**
     * Gets the current executable file
     * @return string Path to executable file
     * @throws SevenZipException
     */
    public function getExecutableFile() {
        if (!empty($this->executableFile))
            return $this->executableFile;
        throw new SevenZipException('7zip executable path is empty!');
    }

    /**
     * Sets the new executable file
     * @param string $executableFile Path to executable file
     * @return bool New executable file has been set
     * @throws SevenZipException
     */
    public function setExecutableFile($executableFile) {
        $path = realpath($executableFile);
        if (is_executable($path)) {
            $this->executableFile = $path;
            return true;
        }
        /*
         * Check if it exists on PATH
         */
        if (SevenZipCommand::isExecutable($executableFile)) {
            $this->executableFile = $executableFile;
            return true;
        }
        throw new SevenZipException('Could not find 7-Zip executable!(' . $executableFile . ')');
    }

    /**
     * Set the base archive path
     * @param string $archivePath
     */
    public function setArchivePath($archivePath) {
        $this->archivePath = $archivePath;
    }

    /**
     * Gets the path of the currently open archive file
     * @return string Currently open archive file
     */
    public function getArchivePath() {
        return $this->archivePath;
    }

    /**
     * Gets the current password
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Sets a new password
     * @param string $password
     */
    protected function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Encrypt archive headers
     * @return bool Encrypt archive headers
     */
    public function getEncryptArchiveHeaders() {
        return $this->encryptArchiveHeaders;
    }

    /**
     * Encrypt archive headers
     * @param bool $encryptArchiveHeaders Encrypt archive headers
     */
    public function setEncryptArchiveHeaders($encryptArchiveHeaders) {
        $this->encryptArchiveHeaders = $encryptArchiveHeaders;
    }

}

?>
