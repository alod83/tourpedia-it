<?php

/**
 * Contains class ExtractFiles
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
 * Command to extract files from the archive
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
class ExtractFiles extends PasswordProtectedCommand {

    /**
     * Destination where to extract the files
     * @var string
     */
    protected $destination;

    /**
     * The entries to extract.
     * @var array
     */
    protected $entries;

    /**
     * Gets the command
     * @return string
     */
    protected function getCommand() {
        $cmd = $this->getExecutable() . " " . self::CMD_EXTRACT_WITH_FULL_PATHS . " " .
                $this->getBaseArchiveFile() . " -y " . $this->getPasswordSwitches() .
                " -o" . $this->getDestination() . " ";
        if (is_array($this->entries)) {
            foreach ($this->entries as $entry) {
                $cmd .= " -ir!" . escapeshellarg($entry);
            }
        }
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
     * Get Destination
     * @return string Destination
     */
    public function getDestination() {
        return $this->destination;
    }

    /**
     * Set Destination
     * @param string $destination
     */
    public function setDestination($destination) {
        $this->destination = escapeshellarg($destination);
    }

    /**
     * Get entries
     * @return array
     */
    public function getEntries() {
        return $this->entries;
    }

    /**
     * Set entries
     * @param array $entries
     */
    public function setEntries($entries) {
        $this->entries = $entries;
    }

}

?>
