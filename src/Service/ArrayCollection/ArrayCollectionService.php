<?php

namespace App\Service\ArrayCollection;

use Doctrine\Common\Collections\ArrayCollection;

class ArrayCollectionService
{
    /**
     * @param ArrayCollection $acA
     * @param ArrayCollection $acB
     * @return ArrayCollection
     */
    public function getNotInA(ArrayCollection $acA, ArrayCollection $acB): ArrayCollection
    {
        return $acB->filter(function ($b) use ($acA) {
            return $acA->contains($b) === false;
        });
    }

    /**
     * @param ArrayCollection $acA
     * @param ArrayCollection $acB
     * @return ArrayCollection
     */
    public function getNotInB(ArrayCollection $acA, ArrayCollection $acB): ArrayCollection
    {
        return $acA->filter(function ($a) use ($acB) {
            return $acB->contains($a) === false;
        });
    }

    /**
     * @param ArrayCollection $acA
     * @param ArrayCollection $acB
     * @return ArrayCollection
     */
    public function getCommon(ArrayCollection $acA, ArrayCollection $acB): ArrayCollection
    {
        return $acA->filter(function ($a) use ($acB) {
            return $acB->contains($a) === true;
        });
    }
}