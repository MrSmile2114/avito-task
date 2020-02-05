<?php


namespace App\Service\EntityServices;


use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractEntityService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $objectRepository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectRepository $repository,
        SerializerInterface $serializer
    ) {
        $this->entityManager = $entityManager;
        $this->objectRepository = $repository;
        $this->serializer = $serializer;
    }

    public function getEntityData(
        int $id,
        string $optionalFields,
        array $allowedOptFields,
        array $defaultFields
    ): ?array {
        $item = $this->objectRepository->find($id);
        if (!is_null($item)) {
            return $this->normalizeEntity($item, $optionalFields, $allowedOptFields, $defaultFields);
        } else {
            return null;
        }
    }

    public function getEntitiesPageData(
        int $page,
        int $resOnPage,
        string $orderBy,
        string $optionalFields,
        array $allowedOptFields,
        array $orderlyFields,
        string $defaultOrder,
        array $defaultFields,
        array $criteria = null
    ) {
        $orderCriteria = $this->getOrderCriteria($orderBy, $orderlyFields, $defaultOrder);
        $criteria = $criteria ?? [];

        $items = $this->objectRepository->findBy(
            $criteria,
            $orderCriteria,
            $resOnPage,
            ($page - 1) * $resOnPage
        );

        $itemsData = [];
        foreach ($items as $item) {
            $itemData = $this->normalizeEntity($item, $optionalFields, $allowedOptFields, $defaultFields);
            $itemsData[] = $itemData;
        }

        return $itemsData;
    }

    public function getOrderCriteria(string $orderBy, array $orderlyFields, string $defaultOrder): array
    {
        $orderCriteria = [];
        $orderBy = str_replace(['asc_', 'asc(', 'ASC_', 'ASC('], '+', $orderBy);
        $orderBy = str_replace(['desc_', 'desc(', 'DESC_', 'DESC('], '-', $orderBy);
        while ((false !== strpos($orderBy, '+')) or (false !== strpos($orderBy, '-'))) {
            $posA = strpos($orderBy, '+');
            $posD = strpos($orderBy, '-');
            $pos = ((false !== $posA) and (false !== $posD))
                ? (($posD < $posA) ? $posD : $posA)
                : ((false !== $posD) ? $posD : $posA);
            $ascDesc = substr($orderBy, $pos, 1);
            $orderBy = substr($orderBy, $pos + 1);
            foreach ($orderlyFields as $field) {
                if (substr($orderBy, 0, strlen($field)) == $field) {
                    $orderCriteria[$field] = ('+' === $ascDesc) ? 'asc' : 'desc';
                }
            }
        }

        if (empty($orderCriteria) and ($orderBy !== $defaultOrder)) {
            $orderCriteria = $this->getOrderCriteria($defaultOrder, $orderlyFields, $defaultOrder);
        }

        return $orderCriteria;
    }

    private function normalizeEntity(
        $item,
        string $optionalFields,
        array $allowedOptFields,
        array $defaultFields
    ): array {
        $itemData = [];
        foreach ($this->serializer->normalize($item) as $itemField => $itemFieldValue) {
            if (
                in_array($itemField, $defaultFields)
                or (
                    in_array($itemField, $allowedOptFields)
                    and (false !== strpos($optionalFields, $itemField))
                )
            ) {
                $itemData[$itemField] = $itemFieldValue;
            }
        }

        return $itemData;
    }

    public function getEntityCount($criteria = null): int
    {
        $criteria = $criteria ?? [];

        return $this->objectRepository->count($criteria);
    }
}
