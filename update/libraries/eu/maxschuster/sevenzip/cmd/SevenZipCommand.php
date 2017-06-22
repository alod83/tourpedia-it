<?php

/**
 * Contains class SevenZipCommand
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

namespace eu\maxschuster\sevenzip\cmd;

use eu\maxschuster\sevenzip\exception\SevenZipException;

/**
 * Base class for all SevenZipArchive commands
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
abstract class SevenZipCommand implements CommandInterface {
    /*
     * Command Line Commands
     */

    /**
     * Adds files to archive.
     * @const CMD_ADD
     */

    const CMD_ADD = 'a';

    /**
     * Measures speed of the CPU and checks
     * RAM for errors.
     * @const CMD_BENCHMARK
     */
    const CMD_BENCHMARK = 'b';

    /**
     * Deletes files from archive.
     * @const CMD_DELETE
     */
    const CMD_DELETE = 'd';

    /**
     * Extracts files from an archive to the
     * current directory or to the output
     * directory.
     * @const CMD_EXTRACT
     */
    const CMD_EXTRACT = 'e';

    /**
     * Lists contents of archive.
     * @const CMD_LIST
     */
    const CMD_LIST = 'l';

    /**
     * Tests archive files.
     * @const CMD_TEST
     */
    const CMD_TEST = 't';

    /**
     * Renames file inside archive
     * @const CMD_RENAME
     */
    const CMD_RENAME = 'rn';

    /**
     * Update older files in the archive and add files
     * that are not already in the archive.
     * Note: the updating of solid .7z archives can be
     * slow, since it can require some recompression.
     * @const CMD_UPDATE
     */
    const CMD_UPDATE = 'u';

    /**
     * Extracts files from an archive with their full
     * paths in the current directory, or in an output
     * directory if specified.
     * @const CMD_EXTRACT_WITH_FULL_PATHS
     */
    const CMD_EXTRACT_WITH_FULL_PATHS = 'x';

    /*
     * 7-Zip exit codes
     */

    /**
     * No error
     * @const EXIT_NO_ERROR
     */
    const EXIT_NO_ERROR = 0;

    /**
     * Warning (Non fatal error(s)). For example,
     * one or more files were locked by some other
     * application, so they were not compressed.
     * @const EXIT_WARNING
     */
    const EXIT_WARNING = 1;

    /**
     * Fatal error
     * @const EXIT_FATAL_ERROR
     */
    const EXIT_FATAL_ERROR = 2;

    /**
     * Command line error
     * @const EXIT_COMMAND_LINE_ERROR
     */
    const EXIT_COMMAND_LINE_ERROR = 7;

    /**
     * Not enough memory for operation
     * @const EXIT_NOT_ENOUGH_MEMORY
     */
    const EXIT_NOT_ENOUGH_MEMORY = 8;

    /**
     * User stopped the process
     * @const EXIT_USER_STOPED_PROCESS
     */
    const EXIT_USER_STOPED_PROCESS = 255;

    /**
     * Array to cache isCmd results
     * @var array
     */
    protected static $isCmdCache = array();

    /**
     * Path to 7-Zip executable file
     * @var string
     */
    protected $executable;

    /**
     * Contains the last generated output
     * @var array
     */
    protected $lastOutput = array();

    /**
     * Last return value
     * @var int
     */
    protected $lastReturnVar;

    /**
     * Gets the current 7-Zip executable file
     * @return string 7-Zip executable file
     */
    public function getExecutable() {
        return $this->executable;
    }

    /**
     * Sets the 7-Zip executable file
     * @param string $executable 7-Zip executable file
     * @param bool $trust Trust the executable without checking
     */
    public function setExecutable($executable, $trust = false) {
        if ($trust || self::isExecutable($executable)) {
            $this->executable = escapeshellarg($executable);
            return;
        }
        throw new SevenZipException("Given file ('" . $executable . "') is not executable!");
    }

    /**
     * Gets the last generated output as array  
     * @return array Last generated output
     */
    public function getLastOutput() {
        return $this->lastOutput;
    }

    /**
     * Sets the last generated output
     * @param array $lastOutput Last generated output
     */
    protected function setLastOutput($lastOutput) {
        $this->lastOutput = $lastOutput;
    }

    /**
     * Gets the last generated return var
     * @return int Last generated return var
     */
    public function getLastReturnVar() {
        return $this->lastReturnVar;
    }

    /**
     * Sets the last generated return var
     * @param int $lastReturnVar Last generated return var
     */
    protected function setLastReturnVar($lastReturnVar) {
        $this->lastReturnVar = $lastReturnVar;
    }

    /**
     * Command constructor
     * @param string $executable Path to the executable
     */
    function __construct($executable = '7z') {
        $this->setExecutable($executable);
    }

    /**
     * Gets the executable command
     * @return string Command
     */
    abstract protected function getCommand();

    /**
     * Gets the last parsed result
     * @return mixed Parsed result
     */
    abstract public function getLastResult();

    /**
     * Executes this command. Returns the exit
     * code if it was possible to execute this
     * command. Otherwise it will throw an
     * Exception.
     * @return int SevenZipCommand::EXIT_NO_ERROR 
     * or SevenZipCommand::EXIT_WARNING
     * @throws SevenZipException
     */
    public function execute() {
        $output = null;
        $returnVar = null;
        $cmd = $this->getCommand();
        exec($cmd, $output, $returnVar);
        $this->setLastOutput($output);
        $this->setLastReturnVar($returnVar);
        switch ($returnVar) {
            case self::EXIT_NO_ERROR:
                return self::EXIT_NO_ERROR;
                break;
            case self::EXIT_WARNING:
                $errorMsg = "7-Zip has completed the operation with errors!" . "('" . $cmd . "')";
                trigger_error($errorMsg, E_NOTICE);
                return self::EXIT_WARNING;
                break;
            case self::EXIT_FATAL_ERROR:
                throw new SevenZipException('Command has caused a fatal error!' . "('" . $cmd . "')");
                break;
            case self::EXIT_COMMAND_LINE_ERROR:
                throw new SevenZipException('This command has a wrong syntax!' . "('" . $cmd . "')");
                break;
            case self::EXIT_NOT_ENOUGH_MEMORY:
                throw new SevenZipException('Not enough memory for operation!' . "('" . $cmd . "')");
                break;
            case self::EXIT_USER_STOPED_PROCESS:
                throw new SevenZipException('User stoped process!' . "('" . $cmd . "')");
                break;
            default :
                throw new SevenZipException('Unknown error! (\'' . $cmd . '\')');
                break;
        }
    }

    /**
     * This function checks if the given file was included to the PATH env
     * variable and if it is executable. I have found this function in
     * Andrew Moores post on stackoverflow.com and I added a few small changes.
     * @param string $file Name of the executable
     * @link http://stackoverflow.com/questions/2994679/how-to-check-an-exectuables-path-is-correct-in-php#answer-2994750
     * @return boolean Is executable
     */
    public static function isExecutable($file) {
        if (isset(self::$isCmdCache[$file])) {
            return self::$isCmdCache[$file];
        }
        if (is_executable($file)) {
            self::$isCmdCache[$file] = true;
            return true;
        }
        if (realpath($file) == $file) {
            self::$isCmdCache[$file] = false;
            return false; // Absolute Path
        }

        /*
         * Switched from $_ENV to getenv() because $_ENV['PATH'] was empty on
         * my system unless i have triggerd getenv('PATH').
         */
        $PATH = getenv('PATH');
        if (!$PATH) {
            return false;
        }

        $paths = explode(PATH_SEPARATOR, $PATH);

        foreach ($paths as $path) {
            // Make sure it has a trailing slash
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if (
                    is_executable($path . $file) ||
                    /*
                     * Also check for .exe and .bat files on windows
                     */
                    PHP_OS == 'WINNT' && (
                    is_executable($path . $file . '.exe') ||
                    is_executable($path . $file . '.bat')
                    )
            ) {
                self::$isCmdCache[$file] = true;
                return true;
            }
        }
        self::$isCmdCache[$file] = false;
        return false;
    }

}

?>
