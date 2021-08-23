<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

final class Ad
{
    public function __construct(
        private int $id,
        private String $typology,
        private String $description,
        private array $pictures,
        private int $houseSize,
        private ?int $gardenSize = null,
        private ?int $score = null,
        private ?DateTimeImmutable $irrelevantSince = null,
    ) {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTypology()
    {
        return $this->typology;
    }

    public function setTypology($typology)
    {
        $this->typology = $typology;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPictures()
    {
        return $this->pictures;
    }

    public function setPictures($pictures)
    {
        $this->pictures = $pictures;
    }

    public function getHouseSize()
    {
        return $this->houseSize;
    }

    public function setHouseSize($houseSize)
    {
        $this->houseSize = $houseSize;
    }

    public function getGardenSize()
    {
        return $this->gardenSize;
    }

    public function setGardenSize($gardenSize)
    {
        $this->gardenSize = $gardenSize;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setScore($score)
    {
        $this->score = $score;
    }

    public function getIrrelevantSince()
    {
        return $this->irrelevantSince;
    }

    public function setIrrelevantSince($irrelevantSince)
    {
        $this->irrelevantSince = $irrelevantSince;
    }
}
