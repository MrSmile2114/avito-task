<?php

namespace App\Service\EntityServices;

interface ItemServiceInterface
{
    public function getItemData(int $id, string $optionalFields): ?array;

    public function getItemsData(int $page, int $resOnPage, string $orderBy, string $optionalFields): array;

    public function getItemsCount(array $criteria = null): int;
}
