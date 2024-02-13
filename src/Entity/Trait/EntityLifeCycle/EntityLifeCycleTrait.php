<?php

namespace App\Entity\Trait\EntityLifeCycle;

trait EntityLifeCycleTrait
{
    use EntityLifeCycleCreateTrait, EntityLifeCycleUpdateTrait, EntityLifeCycleDeleteTrait, EntityLifeCycleArchiveTrait;

    public function __construct(bool $softDelete = false)
    {
        $this->softDelete = $softDelete;
    }
}