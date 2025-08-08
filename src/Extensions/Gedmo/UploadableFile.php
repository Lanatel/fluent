<?php

namespace LaravelDoctrine\Fluent\Extensions\Gedmo;

use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Exception\InvalidMappingException;
use Gedmo\Uploadable\Mapping\Driver\Fluent as UploadableDriver;
use LaravelDoctrine\Fluent\Buildable;
use LaravelDoctrine\Fluent\Builders\Field;

class UploadableFile implements Buildable
{
    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var string
     */
    private $fieldName;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private static $validTypes = ['Path', 'Name', 'Size', 'MimeType'];

    /**
     * UploadableFile constructor.
     *
     * @param ClassMetadata $classMetadata
     * @param string        $fieldName
     * @param string        $type
     */
    public function __construct(ClassMetadata $classMetadata, $fieldName, $type = 'Name')
    {
        $this->validateType($type);

        $this->classMetadata = $classMetadata;
        $this->fieldName = $fieldName;
        $this->type = "file{$type}Field";
    }

    /**
     * Enable the UploadableFile extension.
     *
     * @return void
     */
    public static function enable()
    {
        foreach (self::$validTypes as $type) {
            Field::macro("asFile$type", function (Field $builder) use ($type) {
                return new static($builder->getClassMetadata(), $builder->getName(), $type);
            });
        }
    }

    /**
     * Execute the build process.
     */
    public function build()
    {
        $this->classMetadata->appendExtension(UploadableDriver::EXTENSION_NAME, [
            $this->type => $this->fieldName,
        ]);
    }

    /**
     * Validate the given type of file field.
     *
     * @param string $type
     *
     * @return void
     */
    private function validateType($type)
    {
        if (!in_array($type, self::$validTypes)) {
            throw new InvalidMappingException(
                'Invalid uploadable field type reference. Must be one of: '.implode(', ', self::$validTypes)
            );
        }
    }
}
