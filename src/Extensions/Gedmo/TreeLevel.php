<?php

namespace LaravelDoctrine\Fluent\Extensions\Gedmo;

use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Exception\InvalidMappingException;
use Gedmo\Tree\Mapping\Driver\Fluent as FluentDriver;
use Gedmo\Tree\Mapping\Validator;
use LaravelDoctrine\Fluent\Buildable;
use LaravelDoctrine\Fluent\Builders\Field;

class TreeLevel implements Buildable
{
    const MACRO_METHOD = 'treeLevel';

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
     * Enable TreeLevel.
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
        if (!(new Validator())->isValidField($this->classMetadata, $this->fieldName)) {
            throw new InvalidMappingException("Tree level field must be 'integer' in class - {$this->classMetadata->name}");
        }

        $this->classMetadata->mergeExtension($this->getExtensionName(), [
            'level' => $this->fieldName,
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
