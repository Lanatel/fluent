<?php

namespace LaravelDoctrine\Fluent\Relations;

use Doctrine\ORM\Mapping\ClassMetadata;
use InvalidArgumentException;
use LaravelDoctrine\Fluent\Buildable;
use LaravelDoctrine\Fluent\Relations\Mappings\Association\ConcreteAssociationMapping;

class AssociationCache implements Buildable
{
    protected string $region;

    protected string $usage;

    /**
     * @var array<string, int>
     */
    protected array $usages = [
        'READ_ONLY'            => ClassMetadata::CACHE_USAGE_READ_ONLY,
        'NONSTRICT_READ_WRITE' => ClassMetadata::CACHE_USAGE_NONSTRICT_READ_WRITE,
        'READ_WRITE'           => ClassMetadata::CACHE_USAGE_READ_WRITE,
    ];

    protected string        $field;

    protected ClassMetadata $metadata;

    public function __construct(
        ClassMetadata $metadata,
        string        $field,
        string|int    $usage = 'READ_ONLY',
        ?string       $region = null
    ) {
        $this->field    = $field;
        $this->metadata = $metadata;
        $this->setRegion($region);
        $this->setUsage($usage);
    }

    public function getUsage(): string
    {
        return $this->usage;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setUsage(string|int $usage): self
    {
        if (is_int($usage)) {
            $this->validate($usage, $this->usages);
        } else {
            $this->validate($usage, array_keys($this->usages));
            $usage = $this->usages[$usage];
        }

        $this->usage = $usage;

        return $this;
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Execute the build process.
     */
    public function build(string $targetEntity = ''): void
    {
        if (!isset($this->metadata->associationMappings[$this->field])) {
            $this->metadata->associationMappings[$this->field] = new ConcreteAssociationMapping(
                $this->field,
                $this->metadata->rootEntityName,
                $targetEntity
            );
        }

        $this->metadata->enableAssociationCache($this->field, [
            'usage'  => $this->getUsage(),
            'region' => $this->getRegion(),
        ]);
    }

    protected function validate(string|int $usage, array $usages): string|int
    {
        if (!in_array($usage, $usages)) {
            throw new InvalidArgumentException(
                '['.$usage.'] is not a valid cache usage. Available: '.implode(', ', array_keys($this->usages))
            );
        }

        return $usage;
    }
}
