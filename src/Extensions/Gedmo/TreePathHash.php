<?php

namespace LaravelDoctrine\Fluent\Extensions\Gedmo;

use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Tree\Mapping\Driver\Fluent as FluentDriver;
use LaravelDoctrine\Fluent\Buildable;
use LaravelDoctrine\Fluent\Builders\Field;

class TreePathHash implements Buildable
{
    const MACRO_METHOD = 'treePathHash';

    /**
     * @var ClassMetadata
     */
    protected $classMetadata;

    /**
     * @var string
     */
    protected $fieldName;

    /**
     * @param ClassMetadata $classMetadata
     * @param string        $fieldName
     */
    public function __construct(ClassMetadata $classMetadata, $fieldName)
    {
        $this->classMetadata = $classMetadata;
        $this->fieldName = $fieldName;
    }

    /**
     * Enable TreePathHash.
     */
    public static function enable()
    {
        Field::macro(self::MACRO_METHOD, function (Field $field) {
            return new static($field->getClassMetadata(), $field->getName());
        });
    }

    /**
     * Execute the build process.
     */
    public function build()
    {
        $this->classMetadata->mergeExtension($this->getExtensionName(), [
            'path_hash' => $this->fieldName,
        ]);
    }

    /**
     * Return the name of the actual extension.
     *
     * @return string
     */
    public function getExtensionName()
    {
        return FluentDriver::EXTENSION_NAME;
    }
}
