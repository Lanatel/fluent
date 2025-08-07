<?php

namespace LaravelDoctrine\Fluent\Extensions;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataFactory;

class ExtensibleClassMetadataFactory extends ClassMetadataFactory
{
    protected EntityManagerInterface $entityManager;

    /**
     * Override to hold a reference to the EntityManager here as well (parent property is private).
     */
    public function setEntityManager(EntityManagerInterface $em): void
    {
        parent::setEntityManager($em);

        $this->entityManager = $em;
    }

    /**
     * Override to implement our custom ClassMetadata object.
     */
    protected function newClassMetadataInstance(string $className): ClassMetadata
    {
        return new ExtensibleClassMetadata($className, $this->entityManager->getConfiguration()->getNamingStrategy());
    }
}
