<?php

namespace App\Entity;

use App\Repository\ShopParametersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ShopParametersRepository::class)]
class ShopParameters
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logoFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $defaultImageProduct = null;

    #[Assert\PositiveOrZero(['message' => 'veuillez rentrer un montant valide.'])]
    #[ORM\Column(nullable: true)]
    private ?float $contribution = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogoFile(): ?string
    {
        return $this->logoFile;
    }

    public function setLogoFile(?string $logoFile): static
    {
        $this->logoFile = $logoFile;

        return $this;
    }

    public function getDefaultImageProduct(): ?string
    {
        return $this->defaultImageProduct;
    }

    public function setDefaultImageProduct(?string $defaultImageProduct): static
    {
        $this->defaultImageProduct = $defaultImageProduct;

        return $this;
    }

    public function getContribution(): ?float
    {
        return $this->contribution;
    }

    public function setContribution(?float $contribution): static
    {
        $this->contribution = $contribution;

        return $this;
    }
}
