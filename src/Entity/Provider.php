<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Assert\Url;

#[ORM\Entity(repositoryClass: ProviderRepository::class)]
class Provider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom de l\'entreprise doit contenir plus de 2 caractères',
        maxMessage: 'Le nom de l\'entreprise ne peut pas contenir plus de 50 caractères',
    )]
    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'L\'adresse doit contenir plus de 2 caractères',
        maxMessage: 'L\'adresse ne peut pas contenir plus de 50 caractères',
    )]
    #[ORM\Column(length: 150)]
    private ?string $adress = null;

    #[Assert\Length(
        min: 5,
        max: 10,
        minMessage: 'un code postal contient minimum 5 caractères',
        maxMessage: 'Maximum 10 caractères',
    )]
    #[ORM\Column(length: 10)]
    private ?string $cp = null;

    #[Assert\Length(
        min: 1,
        max: 50,
        minMessage: 'Le nom de ville ne peut être nul',
        maxMessage: 'Maximum 50 caractères',
    )]
    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[Assert\Length(
        max: 200,
        maxMessage: 'Maximum 200 caractères',
    )]
    #[ORM\Column(length: 200, nullable: true)]
    private ?string $website = null;

    #[Assert\Length(
        max: 200,
        maxMessage: 'Maximum 200 caractères',
    )]
    #[ORM\Column(length: 200, nullable: true)]
    private ?string $socialNetwork = null;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: Product::class, orphanRemoval: true)]
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getSocialNetwork(): ?string
    {
        return $this->socialNetwork;
    }

    public function setSocialNetwork(?string $socialNetwork): static
    {
        $this->socialNetwork = $socialNetwork;

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
            $product->setProvider($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProvider() === $this) {
                $product->setProvider(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
