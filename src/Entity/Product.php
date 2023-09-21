<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use function PHPUnit\Framework\isEmpty;

#[ORM\Entity(repositoryClass: ProductRepository::class)]

class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $designation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    // #[Assert\LessThan(1000.00)]
    private ?float $price = null;

    #[ORM\Column]
    #[Assert\LessThan(20)]
    #[Assert\Positive]
    private ?int $quantity = null;

    #[ORM\Column(nullable: true)]
    #[Assert\LessThan(1000)]
    private ?int $stock = null;

    #[ORM\Column]
    private ?bool $available = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $provider = null;

    #[ORM\Column]
    private ?float $volume = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ingredients = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Assert\LessThan(90)]
    private ?float $alcoholLevel = null;

    #[ORM\Column(nullable: true)]
    #[Assert\LessThan(2600)]
    private ?float $bitterness = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductionType $productionType = null;

    #[ORM\ManyToMany(targetEntity: BeerType::class, inversedBy: 'products')]
    #[Assert\Count(
        min: 1,
        max: 5,
        minMessage: 'Vous devez selectionner au moins un style de bière',
        maxMessage: 'vous ne pouvez pas selectionner plus de 5 types de bières',
    )]
    private Collection $beerTypes;

    #[ORM\Column(length: 150)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagefile = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderDetails::class, orphanRemoval: true)]
    private Collection $orderDetails;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class)]
    private Collection $reviews;

    //Moyenne des notes
    private ?float $average;

    public function __construct()
    {
        $this->createdAt = new \DatetimeImmutable();
        $this->beerTypes = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $Designation): static
    {
        $this->designation = $Designation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {

        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getAlcoholLevel(): ?float
    {
        return $this->alcoholLevel;
    }

    public function setAlcoholLevel(float $alcoholLevel): static
    {
        $this->alcoholLevel = $alcoholLevel;

        return $this;
    }

    public function getBitterness(): ?float
    {
        return $this->bitterness;
    }

    public function setBitterness(float $bitterness): static
    {
        $this->bitterness = $bitterness;

        return $this;
    }

    public function getProductionType(): ?ProductionType
    {
        return $this->productionType;
    }

    public function setProductionType(?ProductionType $productionType): static
    {
        $this->productionType = $productionType;

        return $this;
    }

    /**
     * @return Collection<int, BeerType>
     */
    public function getBeerTypes(): Collection
    {
        return $this->beerTypes;
    }

    public function addBeerType(BeerType $beerType): static
    {
        if (!$this->beerTypes->contains($beerType)) {
            $this->beerTypes->add($beerType);
        }

        return $this;
    }

    public function removeBeerType(BeerType $beerType): static
    {
        $this->beerTypes->removeElement($beerType);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImagefile(): ?string
    {
        return $this->imagefile;
    }

    public function setImagefile(?string $imagefile): static
    {
        $this->imagefile = $imagefile;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetails>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setProduct($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProduct() === $this) {
                $orderDetail->setProduct(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->designation . ' - ' . $this->volume . 'cl';
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of average
     */
    public function getAverage()
    {
        $reviews = $this->reviews;

        if ($reviews->isEmpty()) {
             $this->average = null;
             return $this->average;
        }
        $total = 0;
        foreach ($reviews as $review) {
            $rating = $review->getRating();
            $total = $total + $rating;
        }

        $this->average = $total / count($reviews);

        return $this->average;
    }
}
