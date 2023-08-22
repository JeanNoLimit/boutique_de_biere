<?php

namespace App\Entity;

use App\Repository\BeerTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BeerTypeRepository::class)]
#[UniqueEntity(
    fields: 'name',
    message: "Ce style de bière existe déjà")]
class BeerType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Minumum 2 caractères',
        maxMessage: 'Maximum 30 caractères',
    )]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'beerTypes')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addBeerType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeBeerType($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
