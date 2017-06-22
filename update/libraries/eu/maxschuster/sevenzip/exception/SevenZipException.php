<?php

/**
 * Contains class SevenZipException
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

namespace eu\maxschuster\sevenzip\exception;

use \Exception;

/**
 * Base Exception for SevenZipArchive
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
class SevenZipException extends Exception {

    /**
     * Construct the exception
     * @param string $message [optional]
     * @param int $code [optional]
     * @param Exception $previous [optional]
     */
    public function __construct($message = 'An unknown SevenZip exception occured!', $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}

?>
