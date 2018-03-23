<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 12:10
 */

namespace App\Manager;

use App\Manager\InterfaceDef\BaseManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractManager implements BaseManagerInterface
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function persist($object) {
        $this->entityManager->persist($object);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    public function remove($object)
    {
        $this->entityManager->remove($object);
    }
}