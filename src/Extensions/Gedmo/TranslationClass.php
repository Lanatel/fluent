<?php

namespace LaravelDoctrine\Fluent\Extensions\Gedmo;

use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Translatable\Mapping\Driver\Fluent as FluentDriver;
use LaravelDoctrine\Fluent\Buildable;
use LaravelDoctrine\Fluent\Builders\Builder;

class TranslationClass implements Buildable
{
    const MACRO_METHOD = 'translationClass';

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var string
     */
    private $class;

    /**
     * Locale constructor.
     *
     * @param ClassMetadata $classMetadata
     * @param string        $class
     */
    public function __construct(ClassMetadata $classMetadata, $class)
    {
        $this->classMetadata = $classMetadata;
        $this->class = $class;
    }

    /**
     * @return void
     */
    public static function enable()
    {
        Builder::macro(self::MACRO_METHOD, function (Builder $builder, $class) {
            return new static($builder->getClassMetadata(), $class);
        });
    }

    /**
     * Execute the build process.
     */
    public function build()
    {
        $this->classMetadata->appendExtension($this->getExtensionName(), [
            'translationClass' => $this->class,
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
