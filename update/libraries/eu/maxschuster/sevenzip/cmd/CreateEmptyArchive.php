<?php

/**
 * Contains class CreateEmptyArchive
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
 * Command to create an empty archive
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
class CreateEmptyArchive extends PasswordProtectedCommand {

    /**
     * Gets the command
     * @return string
     */
    protected function getCommand() {
        return $this->getExecutable() . " " .
                self::CMD_ADD . " " .
                $this->getBaseArchiveFile() . " -x!*" .
                $this->getPasswordSwitches();
    }

    /**
     * Gets last result
     * @return boolean
     */
    public function getLastResult() {
        return $this->getLastReturnVar() === self::EXIT_NO_ERROR;
    }

}

?>
