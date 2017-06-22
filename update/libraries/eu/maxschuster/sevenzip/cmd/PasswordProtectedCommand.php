<?php

/**
 * Contains class PasswordProtectedCommand
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
 * Base class for password protected commands
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
abstract class PasswordProtectedCommand extends BaseArchiveAccessCommand {

    /**
     * Password. Value for switch "-p"
     * @var string
     */
    protected $password;

    /**
     * Encrypt archive headers (-mhe switch),
     * so filenames will be encrypted.
     * @var bool
     */
    protected $encryptArchiveHeaders;

    /**
     * Checks if a password was set.
     * @return bool Has password
     */
    public function hasPassword() {
        return !empty($this->password);
    }

    /**
     * Gets the current password
     * @return string Current password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Sets a new password
     * @param string $password New password
     */
    public function setPassword($password) {
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

    /**
     * Gets the right password switches for this command
     * @return string Password switches
     */
    public function getPasswordSwitches() {
        if ($this->hasPassword()) {
            $switch = " -p" . escapeshellarg($this->getPassword());
            if ($this->getEncryptArchiveHeaders()) {
                $switch .= " -mhe ";
            }
            return $switch;
        } else {
            return '';
        }
    }

}

?>
