<?php

namespace App\Controller;

use App\Service\EntityServices\ItemServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ItemsController extends AbstractController
{
    private const DEFAULT_RES_ON_PAGE = 10;
    private const MAX_RES_ON_PAGE = 100000;

    /**
     * @Route("/items", name="items", methods={"GET"})
     *
     * @param Request              $request
     * @param ItemServiceInterface $itemService
     *
     * @return JsonResponse
     */
    public function index(
        Request $request,
        ItemServiceInterface $itemService
    ): JsonResponse {
        $pageNum = $request->get('page', 1);
        $resultsOnPageNum = $request->get('resultsOnPage', self::DEFAULT_RES_ON_PAGE);
        $orderBy = $request->get('orderBy', '');
        $optionalFields = $request->get('fields', '');

        if (!is_numeric($pageNum) or $pageNum <= 0) {
            $pageNum = 1;
        }
        if (!is_numeric($resultsOnPageNum) or $resultsOnPageNum <= 0 or $resultsOnPageNum > self::MAX_RES_ON_PAGE) {
            $resultsOnPageNum = self::DEFAULT_RES_ON_PAGE;
        }
        $itemsCount = $itemService->getItemsCount();
        if (($pageNum - 1) * $resultsOnPageNum >= $itemsCount) {
            $pageNum = 1;
        }

        $nextPageExists = ($pageNum * $resultsOnPageNum) < $itemsCount;

        $itemsData = $itemService->getItemsData($pageNum, $resultsOnPageNum, $orderBy, $optionalFields);

        return $this->json(
            [
                'status' => 'Success',
                'currentPage' => $pageNum,
                'nextPageExists' => $nextPageExists,
                'itemsCount' => count($itemsData),
                'items' => $itemsData,
            ]
        );
    }
}
