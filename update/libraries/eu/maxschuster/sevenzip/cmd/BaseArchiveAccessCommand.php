<?php

/**
 * Contains class BaseArchiveAccessCommand
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
 * Base class for commands that access an archive
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
abstract class BaseArchiveAccessCommand extends SevenZipCommand {

    /**
     * Base archive file
     * @var string
     */
    protected $baseArchiveFile;

    /**
     * Gets the base archive file
     * @return string Base archive file
     */
    public function getBaseArchiveFile() {
        return $this->baseArchiveFile;
    }

    /**
     * Sets the base archive file
     * @param string $baseArchiveFile Base archive file
     */
    public function setBaseArchiveFile($baseArchiveFile) {
        $this->baseArchiveFile = escapeshellarg($baseArchiveFile);
    }

}

?>
