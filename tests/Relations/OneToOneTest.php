<?php

namespace Tests\Relations;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\DefaultNamingStrategy;
use LaravelDoctrine\Fluent\Builders\Traits\Macroable;
use LaravelDoctrine\Fluent\Relations\ManyToOne;
use LaravelDoctrine\Fluent\Relations\OneToOne;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\Builders\IsMacroable;
use Tests\Relations\Traits\OneTo;
use Tests\Relations\Traits\Ownable;
use Tests\Relations\Traits\Owning;
use Tests\Relations\Traits\Primary;

class OneToOneTest extends RelationTestCase
{
    use OneTo;
    use Owning;
    use Ownable;
    use Primary;
    use IsMacroable;
    use MockeryPHPUnitIntegration;

    /**
     * @var ManyToOne
     */
    protected $relation;

    /**
     * @var ClassMetadataBuilder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $field = 'parent';

    protected function setUp(): void
    {
        $this->builder = new ClassMetadataBuilder(new ClassMetadata(
            FluentEntity::class
        ));

        $this->relation = new OneToOne($this->builder, new DefaultNamingStrategy(), $this->field, FluentEntity::class);
    }

    /**
     * Get the builder under test.
     *
     * @return Macroable
     */
    protected function getMacroableBuilder()
    {
        return $this->relation;
    }
}
