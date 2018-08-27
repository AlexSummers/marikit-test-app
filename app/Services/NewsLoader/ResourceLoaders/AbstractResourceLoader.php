<?php

namespace App\Services\NewsLoader\ResourceLoaders;

use App\Services\NewsLoader\Entities;

abstract class AbstractResourceLoader {

    /**
     * @return string
     */
    abstract public function getResourceName(): string;

    /**
     * @return Entities\News[]
     */
    abstract public function loadNews(): array;
}