<?php

namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltersExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('stars', [$this, 'stars'], ['is_safe' => ['html']]),
        ];
    }

    public function stars(float $note)
    {
        $entier = floor($note);
        $decimal = $note - $entier;
        $html = '';
        $count = 0;
        
        for ($i = 0; $i < $entier; $i++) {
            $html .= '<i class="fa-solid fa-star"></i>';
            $count++;
        }

        if ($decimal>0 && $decimal<0.5){
            $html .= '<i class="fa-regular fa-star"></i>';
            $count++;
        }elseif ($decimal>=0.5){
            $html .= '<i class="fa-solid fa-star-half-stroke"></i>';
            $count++;
        }
        for ($i = 0; $i < 5 - $count; $i++) {
            $html .= '<i class="fa-regular fa-star"></i>';
        
        } 

        return $html;

    }

}


?>