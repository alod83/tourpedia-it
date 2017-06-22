<?php

/**
 * Contains class Rename
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
 * Command to rename files inside the archive
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
class Rename extends PasswordProtectedCommand {

    /**
     * Old filename
     * @var string
     */
    protected $name;
    
    /**
     * New filename
     * @var string
     */
    protected $newname;

    /**
     * Gets the command
     * @return string
     */
    protected function getCommand() {

        $cmd = $this->getExecutable() . " " .
                self::CMD_RENAME . " " . $this->getBaseArchiveFile() . " -y " .
                $this->getPasswordSwitches() . " " .
                $this->getName() . " " . $this->getNewname();

        return $cmd;
    }

    /**
     * Gets the last result
     * @return boolean Result
     */
    public function getLastResult() {
        return $this->getLastReturnVar() === self::EXIT_NO_ERROR;
    }

    /**
     * Get name
     * @return string Name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set name
     * @param string $name
     */
    public function setName($name) {
        $this->name = escapeshellcmd($name);
    }

    /**
     * Get new name
     * @return string
     */
    public function getNewname() {
        return $this->newname;
    }

    /**
     * Set new name
     * @param string $newname
     */
    public function setNewname($newname) {
        $this->newname = escapeshellcmd($newname);
    }

}

?>
