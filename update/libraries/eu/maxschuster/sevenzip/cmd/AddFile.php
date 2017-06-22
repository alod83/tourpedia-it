<?php

/**
 * Contains class AddFile
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

/**
 * Command to add a file to the archive
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
class AddFile extends PasswordProtectedCommand {

    /**
     * File to add
     * @var string
     */
    protected $filename;
    
    /**
     * Name of the file inside the archive
     * @var string
     */
    protected $localname = NULL;

    /**
     * Gets the command
     * @return string
     */
    protected function getCommand() {

        $localeName = escapeshellarg(!empty($this->localname) ? $this->localname : $this->filename);
        $fileName = escapeshellarg(realpath($this->filename));

        $cmd = $this->getExecutable() . " " .
                self::CMD_ADD . " " . $this->getBaseArchiveFile() . " -y " .
                $this->getPasswordSwitches() . " " .
                "-si" . $localeName . " < " . $fileName;

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
     * Get filename
     * @return string
     */
    public function getFilename() {
        return $this->filename;
    }

    /**
     * Set filename
     * @param string $filename
     */
    public function setFilename($filename) {
        $this->filename = $filename;
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
        $this->localname = $localname;
    }

}

?>
