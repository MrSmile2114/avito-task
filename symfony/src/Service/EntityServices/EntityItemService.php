<?php

namespace App\Service\EntityServices;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class EntityItemService extends AbstractEntityService implements ItemServiceInterface
{
    private const ALLOWED_OPTIONAL_RESP_FIELDS = ['description', 'imgLinks', 'created', 'imgLinksArr'];
    private const DEFAULT_RESP_FIELDS = ['name', 'price', 'mainImgLink', 'id'];

    private const DEFAULT_ORDER = '-created';
    private const ORDERLY_FIELDS = ['name', 'price', 'id', 'created'];

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        parent::__construct($entityManager, $entityManager->getRepository(Item::class), $serializer);
    }

    public function getItemsData(int $page, int $resOnPage, string $orderBy, string $optRespFields): array
    {
        return $this->getEntitiesPageData(
            $page,
            $resOnPage,
            $orderBy,
            $optRespFields,
            self::ALLOWED_OPTIONAL_RESP_FIELDS,
            self::ORDERLY_FIELDS,
            self::DEFAULT_ORDER,
            self::DEFAULT_RESP_FIELDS
        );
    }

    public function getItemData(int $id, string $optionalFields): ?array
    {
        return $this->getEntityData(
            $id,
            $optionalFields,
            self::ALLOWED_OPTIONAL_RESP_FIELDS,
            self::DEFAULT_RESP_FIELDS
        );
    }

    public function getItemsCount(array $criteria = null): int
    {
        return $this->count($criteria);
    }
}
