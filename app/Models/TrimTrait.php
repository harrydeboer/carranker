<?php

declare(strict_types=1);

namespace App\Models;

trait TrimTrait
{
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getFullName(): string
    {
        $name = $this->make . ' ' . $this->model . ' ' . $this->year_begin
            . '-' . $this->year_end . ' ' . $this->framework;

        return is_null($this->getName()) ? $name : $name . ' ' . $this->getName();
    }

    public function getRatingFiltering(): float
    {
        return $this->rating;
    }

    public function setRatingFiltering(float $rating): void
    {
        $this->rating = $rating;
    }

    /** The images are stored in directories with names that contain no special characters.
     * To get the image url the special characters must be replaced by normal characters. */
    public function getImage(): string
    {
        $image = '/img/models/';
        $image .= str_replace(' ', '_', preg_replace("/&([a-z])[a-z]+;/i",
                "$1", htmlentities($this->make))) . '_';
        $image .= str_replace(' ', '_', preg_replace("/&([a-z])[a-z]+;/i",
                "$1", htmlentities($this->model))) . '.jpg';

        $root = dirname(__DIR__, 2);
        if (!file_exists($root . '/public/' . $image)) {
            $image = '';
        }

        return $image;
    }

    public function getPrice(float $FXRate): ?float
    {
        return $this->price * $FXRate;
    }

    public function getUrl(): string
    {
        return '/model/' . rawurlencode($this->make) . '/' . rawurlencode($this->model) . '?trimId=' . $this->getId();
    }

    public function getMakename(): string
    {
        return $this->make;
    }

    public function getModelname(): string
    {
        return $this->model;
    }

    public function getYearBegin(): int
    {
        return $this->year_begin;
    }

    public function getYearEnd(): int
    {
        return $this->year_end;
    }

    public function getVotes(): int
    {
        return $this->votes;
    }

    public function getFramework(): ?string
    {
        return $this->framework;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function getTransmission(): ?string
    {
        return $this->gearbox_type;
    }

    public function getNumberOfDoors(): ?int
    {
        return $this->number_of_doors;
    }

    public function getNumberOfSeats(): ?int
    {
        return $this->number_of_seats;
    }

    public function getNumberOfGears(): ?int
    {
        return $this->number_of_gears;
    }

    public function getMaxTrunkCapacity(): ?int
    {
        return $this->max_trunk_capacity;
    }

    public function getEngineCapacity(): ?float
    {
        return $this->engine_capacity;
    }

    public function getFueltankCapacity(): ?int
    {
        return $this->fueltank_capacity;
    }

    public function getMaxSpeed(): ?int
    {
        return $this->max_speed;
    }

    public function getFullWeight(): ?int
    {
        return $this->full_weight;
    }

    public function getEnginePower(): ?int
    {
        return $this->engine_power;
    }

    public function getAcceleration(): ?float
    {
        return $this->acceleration;
    }

    public function getFuelConsumption(): ?float
    {
        return $this->fuel_consumption;
    }

    public function getFrameworkImage(): string
    {
        return '/img/' . $this->framework . '.png';
    }

    public function getFuelImage(): string
    {
        $fuel = $this->fuel;
        if ($fuel === 'Gasoline,  Electric' || $fuel === 'Gasoline,  CNG') {
            return '/img/Gasoline.png';
        }
        return '/img/' . $fuel . '.png';
    }

    public function setVotes(int $votes): void
    {
        $this->votes = $votes;
    }
}