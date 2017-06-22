<?php

/**
 * Contains class TestArchive
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
 * 7-Zip command to test an existing Archive
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
class TestArchive extends PasswordProtectedCommand {

    /**
     * Gets the command
     * @return string Command
     */
    protected function getCommand() {
        return $this->getExecutable() . " " . self::CMD_TEST
                . " " . $this->getBaseArchiveFile() .
                $this->getPasswordSwitches();
    }

    /**
     * Gets the last result
     * @return boolean Result
     */
    public function getLastResult() {
        return $this->getLastReturnVar() === self::EXIT_NO_ERROR;
    }

    /**
     * Executs the command
     * @return int No error
     */
    public function execute() {
        // Avoid throwing exceptions when testing.
        // Use getLastResult to check if it has worked.
        try {
            $result = parent::execute();
            unset($result);  // Avoid ugly netbeans warning ;-)
        } catch (SevenZipException $ex) {
            unset($ex); // Avoid ugly netbeans warning ;-)
        }
        return self::EXIT_NO_ERROR;
    }

}

?>
