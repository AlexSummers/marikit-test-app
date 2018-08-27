<?php

namespace App\Services\NewsLoader\Entities;

class News {

    /** @var string */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /** @var \DateTime */
    private $publicationDate;

    /** @var string|null */
    private $mainImageUrl;

    /**
     * @param string $id
     * @param string $title
     * @param string $content
     * @param \DateTime $publicationDate
     * @param string|null $mainImageUrl
     */
    public function __construct(string $id, string $title, string $content, \DateTime $publicationDate, string $mainImageUrl = null) {
        $this->id              = $id;
        $this->title           = $title;
        $this->content         = $content;
        $this->publicationDate = $publicationDate;
        $this->mainImageUrl    = $mainImageUrl;
    }

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate(): \DateTime {
        return $this->publicationDate;
    }

    /**
     * @return null|string
     */
    public function getMainImageUrl(): ?string {
        return $this->mainImageUrl;
    }
}