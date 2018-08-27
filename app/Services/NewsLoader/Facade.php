<?php

namespace App\Services\NewsLoader;

class Facade {

    /** @var Loader */
    private $loader;

    /**
     * @param Loader $loader
     */
    public function __construct(Loader $loader) {
        $this->loader = $loader;
    }

    /**
     * @return \App\Models\News[]
     */
    public function initNewsHandler() {
        return $this->loader->initNewsHandler();
    }
}