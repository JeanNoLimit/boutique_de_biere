<?php

namespace App\Entity;

use App\Repository\ProductionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ProductionTypeRepository::class)]
#[UniqueEntity('productionType')]
class ProductionType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Minimum 2 caractères',
        maxMessage: 'Maximum 20 caractères',
    )]
    private ?string $productionType = null;

    #[ORM\OneToMany(mappedBy: 'productionType', targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductionType(): ?string
    {
        return $this->productionType;
    }

    public function setProductionType(string $productionType): static
    {
        $this->productionType = $productionType;

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
            $product->setProductionType($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductionType() === $this) {
                $product->setProductionType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getProductionType();
    }
}
