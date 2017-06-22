<?php

/**
 * Contains class AddFileFromString
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
use eu\maxschuster\sevenzip\SevenZipArchive;

/**
 * Command to add a file from a string to the archive
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
class AddFileFromString extends PasswordProtectedCommand {
    /**
     * Name of file inside the archive
     * @var string
     */
    protected $localname;
    
    /**
     * Filecontents
     * @var string
     */
    protected $contents;

    /**
     * Gets the command
     * @return string
     */
    protected function getCommand() {
        
        $cmd = trim($this->getExecutable(),'"') . " " .
                self::CMD_ADD . " " . $this->getBaseArchiveFile() . " " .
                $this->getPasswordSwitches() . " -y " .
                "-si" . $this->getLocalname();
        
        return $cmd;
    }

    /**
     * Gets the last result
     * @return boolean
     */
    public function getLastResult() {
        return $this->getLastReturnVar() === self::EXIT_NO_ERROR;
    }

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
        $cmd = $this->getCommand();
        if (PHP_OS === "WINNT" && SevenZipArchive::$winDoubleQuoteFix) {
            $cmd = "\"" . $cmd . "\"";
        }
        // use proc_open so we can write into stdin
        $desc = array(
            0 => array("pipe", "r"), // stdin
            1 => array("pipe", "w"), // stout
            2 => array("pipe", "w") // sterr
        );
        $pipes = array();
        $proc = proc_open($cmd, $desc, $pipes);
        fwrite($pipes[0], $this->getContents());
        fclose($pipes[0]);
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $returnVar = proc_close($proc);

        $this->setLastOutput($output);
        $this->setLastReturnVar($returnVar);
        
        switch ($returnVar) {
            case self::EXIT_NO_ERROR:
                return self::EXIT_NO_ERROR;
                break;
            case self::EXIT_WARNING:
                $errorMsg = "7-Zip has completed the operation with errors!" . "('" . $cmd . "')";
                trigger_error($errorMsg, E_USER_NOTICE);
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
     * Get localname
     * @return string
     */
    public function getLocalname() {
        return $this->localname;
    }

    /**
     * Set localname
     * @param string $localname
     */
    public function setLocalname($localname) {
        $this->localname = ($localname);
    }

    /**
     * Get contents
     * @return string
     */
    public function getContents() {
        return $this->contents;
    }

    /**
     * Set contents
     * @param string $contents
     */
    public function setContents($contents) {
        $this->contents = $contents;
    }

}

?>
