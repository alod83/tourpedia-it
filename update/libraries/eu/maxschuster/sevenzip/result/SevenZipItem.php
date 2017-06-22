<?php

/**
 * Contains class SevenZipItem
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

namespace eu\maxschuster\sevenzip\result;

use \DateTime;

/**
 * Base class for items from the file listing
 *
 * @author Max Schuster <dev@maxschuster.eu>
 * @copyright (c) 2012, Max Schuster
 * @package sevenziparchive
 */
abstract class SevenZipItem {
    
    /**
     * Name
     * @var string
     */
    protected $name;
    
    /**
     * Date
     * @var DateTime
     */
    protected $date;
    
    /**
     * Attributes
     * @var string
     */
    protected $attributes;
    
    /**
     * Size
     * @var int 
     */
    protected $size;
    
    /**
     * Size compressed
     * @var int
     */
    protected $sizeCompressed;

    /**
     * Constructor
     * @param DateTime $date
     * @param string $attributes
     * @param int $size
     * @param int $sizeCompressed
     * @param string $name
     */
    function __construct($date, $attributes, $size, $sizeCompressed, $name) {
        $this->name = $name;
        $this->date = $date;
        $this->attributes = $attributes;
        $this->size = $size;
        $this->sizeCompressed = $sizeCompressed;
    }
    
    /**
     * Get name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Get date
     * @return DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Get attributes
     * @return string
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * Get size
     * @return int
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Get size compressed
     * @return int
     */
    public function getSizeCompressed() {
        return $this->sizeCompressed;
    }
    
}

?>
