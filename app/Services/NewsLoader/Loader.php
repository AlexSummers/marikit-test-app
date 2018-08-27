<?php

namespace App\Services\NewsLoader;

use App\Models;

class Loader {

    /**
     * @return ResourceLoaders\AbstractResourceLoader[]
     */
    private function getResourceLoaders(): array {
        return [
            new ResourceLoaders\RbkLoader(),
        ];
    }

    /**
     * @return Models\News[]
     */
    public function initNewsHandler(): array {
        $result = [];
        foreach ($this->getResourceLoaders() as $resourceLoader) {
            $newsList       = $resourceLoader->loadNews();
            $storedNewsList = $this->storeNewsList($newsList, $resourceLoader->getResourceName());
            $result         = array_merge($storedNewsList, $result);
        }
        return $result;
    }

    /**
     * @param Entities\News[] $newsList
     * @param string $resourceName
     * @return Models\News[] $newsList
     */
    private function storeNewsList(array $newsList, string $resourceName): array {
        $result = [];
        // Для оптиммизации тут должно быть массовое replace into, однако eloquent подддерживает только построчное replace into
        foreach ($newsList as $news) {
            $attributes = [
                'external_id'      => $news->getId(),
                'resource_name'    => $resourceName,
            ];
            $values = [
                'title'            => $news->getTitle(),
                'content'          => $news->getContent(),
                'publication_date' => $news->getPublicationDate(),
                'main_image_url'   => $news->getMainImageUrl(),
            ];
            $result[] = Models\News::updateOrCreate($attributes, $values);
        }
        return $result;
    }
}