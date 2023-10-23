<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Filters
{



    #[Assert\Length(
        max: 30,
        maxMessage: 'Votre recherche ne peut pas contenir plus de {{ limit }} caractères',
    )]
    public ?string $searchProduct = '';


    public ?array $providers = [];

    public ?array $beerTypes = [];

    #[Assert\PositiveOrZero(
        message: 'Veuillez selectionner un prix minimum supérieur à 0€'
    )]
    #[Assert\LessThan(
        propertyPath:"max",
        message: 'Veuillez rentrer une valeur min inférieur à la valeur max.'
    )]
    public ?float $min;

    #[Assert\GreaterThan(
        propertyPath:"min",
        message: 'Veuillez rentrer une valeur max supérieur à la valeur min.'
    )]
    #[Assert\PositiveOrZero(
        message: 'Veuillez selectionner un prix max supérieur à 0€'
    )]
    public ?float $max;

    #[Assert\PositiveOrZero(
        message: 'Veuillez selectionner un taux d\'alcool sup. à 0°'
    )]
    #[Assert\LessThanOrEqual(
        value: '90',
        message: 'Le taux d\'alccol ne peut pas depasser 90°'
    )]
    public ?float $tauxMin;

    #[Assert\PositiveOrZero(
        message: 'Veuillez selectionner un taux d\'alcool sup. à 0°'
    )]
    #[Assert\LessThanOrEqual(
        value: '90',
        message: 'Le taux d\'alccol ne peut pas depasser 90°'
    )]
    public ?float $tauxMax;
    
    #[Assert\Positive]
    public int $page = 1;
    
    public ?string $sort;

    public ?string $direction;

}
