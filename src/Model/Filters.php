<?php

namespace App\Model;

class Filters
{
    public ?string $searchProduct = '';

    public array $providers = [];

    public array $beerTypes = [];

    public ?float $min;

    public ?float $max;

    public ?float $tauxMin;

    public ?float $tauxMax;
}
