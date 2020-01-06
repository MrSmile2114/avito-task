<?php


namespace App\Services;


use Symfony\Component\HttpFoundation\JsonResponse;

class ItemService extends AbstractService
{
    public function createItemByApiData(string $data): JsonResponse
    {
        return new JsonResponse();
    }

}