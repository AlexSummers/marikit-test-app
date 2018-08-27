<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller {

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse {
        $newsList = News::query()->orderBy('publication_date')->limit(15)->get();
        $converterResponse = [];
        foreach ($newsList as $news) {
            $converterResponse[] = [
                'id'              => $news->id,
                'externalId'      => $news->external_id,
                'resourceName'    => $news->resource_name,
                'title'           => $news->title,
                'publicationDate' => $news->publication_date,
                'shortContent'    => mb_substr($news->content, 0, 200),
            ];
        }
        return new JsonResponse(['data' => $converterResponse, 'status' => 'success']);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse {
        $news = News::query()->where('id', '=', $id)->get()->first();
        if ($news == null) {
            $result = ['data' => null, 'status' => 'error'];
        } else {
            $converterResponse = [
                'id'              => $news->id,
                'externalId'      => $news->external_id,
                'resourceName'    => $news->resource_name,
                'title'           => $news->title,
                'publicationDate' => $news->publication_date,
                'content'         => $news->content,
                'mainImageUrl'    => $news->main_image_url,
            ];
            $result = ['data' => $converterResponse, 'status' => 'success'];
        }
        return new JsonResponse($result);
    }
}