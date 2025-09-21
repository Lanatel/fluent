<?php

namespace LaravelDoctrine\Fluent\Builders;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use Doctrine\ORM\Mapping\NamingStrategy;
use LaravelDoctrine\Fluent\Extensions\ExtensibleClassMetadata;

abstract class AbstractBuilder
{
    /**
     * @var ClassMetadataBuilder
     */
    protected $builder;

    /**
     * @var NamingStrategy
     */
    protected $namingStrategy;

    /**
     * @param ClassMetadataBuilder $builder
     * @param NamingStrategy|null  $namingStrategy
     */
    public function __construct(ClassMetadataBuilder $builder, ?NamingStrategy $namingStrategy = null)
    {
        $this->builder = $builder;
        $this->namingStrategy = $namingStrategy ?: new DefaultNamingStrategy();
    }

    /**
     * @return ClassMetadataBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return ClassMetadata|ExtensibleClassMetadata
     */
    public function getClassMetadata()
    {
        return $this->builder->getClassMetadata();
    }

    /**
     * @return NamingStrategy
     */
    public function getNamingStrategy()
    {
        return $this->namingStrategy;
    }
}
