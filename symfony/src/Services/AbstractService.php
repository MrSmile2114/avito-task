<?php


namespace App\Services;


use Doctrine\Persistence\ManagerRegistry;


abstract class AbstractService
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * AbstractService constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
}