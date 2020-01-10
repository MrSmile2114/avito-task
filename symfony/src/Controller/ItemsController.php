<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ItemsController extends AbstractController
{
    private $defaultResultsOnPage = 10;
    private $maxResultsOnPage = 100000;
    private $defaultOrder = '-created';

    private $responseFields = ['name', 'price', 'mainImgLink'];
    private $orderlyFields = ['name', 'price', 'id', 'created'];

    /**
     * @Route("/items", name="items", methods={"GET"})
     * @param Request $request
     * @param ItemRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function index(Request $request, ItemRepository $repository, SerializerInterface $serializer)
    {
        $pageNum = $request->get('page', 1);
        $resultsOnPageNum = $request->get('resultsOnPage', $this->defaultResultsOnPage);
        $orderBy = $request->get('orderBy', $this->defaultOrder);

        if (!is_numeric($pageNum) or $pageNum <= 0) {
            $pageNum = 1;
        }
        if (!is_numeric($resultsOnPageNum) or $resultsOnPageNum <= 0 or $resultsOnPageNum >= $this->maxResultsOnPage) {
            $resultsOnPageNum = $this->defaultResultsOnPage;
        }

        if (($pageNum - 1) * $resultsOnPageNum > $repository->count([])){
            $pageNum = 1;
        }

        $orderCriteria = $this->getOrderCriteria($orderBy);

        $nextPageExists = false;
        $items = $repository->findBy([], $orderCriteria, $resultsOnPageNum + 1, ($pageNum - 1) * $resultsOnPageNum);
        if (array_key_exists($resultsOnPageNum, $items)) {
            unset($items[$resultsOnPageNum]);
            $nextPageExists = true;
        }

        $itemsData = [];
        foreach ($items as $item) {
            foreach ($serializer->normalize($item) as $itemField => $itemFieldValue) {
                if (in_array($itemField, $this->responseFields)) {
                    $itemsData[$item->getId()][$itemField] = $itemFieldValue;
                }
            }
        }

        return $this->json([
            'status' =>         'Success',
            'currentPage' =>    $pageNum,
            'nextPageExists' => $nextPageExists,
            'items' =>          $itemsData,
        ]);

    }

    public function getOrderCriteria(string $orderBy): array
    {
        $orderCriteria = [];
        $orderBy = str_replace(['asc_', 'asc(', 'ASC_', 'ASC('], '+', $orderBy);
        $orderBy = str_replace(['desc_', 'desc(', 'DESC_', 'DESC('], '-', $orderBy);
        while ((strpos($orderBy, '+') !== false) or (strpos($orderBy, '-') !== false)) {
            $pos = strpos($orderBy, '+');
            $posD = strpos($orderBy, '-');
            if ($posD < $pos) {
                $pos = $posD;
            }
            $ascDesc = substr($orderBy, $pos, 1);
            $orderBy = substr($orderBy, $pos + 1);
            foreach ($this->orderlyFields as $field) {
                if (substr($orderBy, 0, strlen($field)) == $field) {
                    if ($ascDesc == '+') {
                        $orderCriteria[$field] = 'asc';
                    } else {
                        $orderCriteria[$field] = 'desc';
                    }

                }
            }
        }
        return $orderCriteria;
    }
}
