<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\EntityListener\UserListener;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[UniqueEntity(
    fields: ['email', 'pseudo'],
    message: '{{ label }} déjà utilisé'
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners([UserListener::class])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length([
        'min' => '2',
        'max' => '180',
        'minMessage' => 'Veuillez renseigner une adresse mail valide',
        'maxMessage' => 'Veuillez renseigner une adresse mail valide'
    ])]
    #[Assert\Email([
        'message' => 'Veuillez renseigner une adresse mail valide'
        ])
    ]
    #[Assert\NotBlank([
        'message' => 'veuillez renseigner une adresse mail'
    ])]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9-_\.]+@[a-zA-z0-9]+.[a-zA-Z]{2,3}/',
        message: 'Veuillez renseigner une adresse mail valide'
    )]
    private ?string $email = null;

    #[ORM\Column(length: 15, unique: true)]
    #[Assert\NotBlank([
        'message' => 'veuillez renseigner un pseudo'
    ])]
    #[Assert\Length([
        'min' => '2',
        'max' => '15',
        'minMessage' => 'Veuillez selectionner un pseudo de plus de {{ limit }} caractères',
        'maxMessage' => 'Veuillez selectionner un pseudo de moins de {{ limit }} caractères'
    ])]
    #[Assert\Regex(
        pattern: '/^[^@\s<>"\']+$/',
        message: 'Votre pseudo ne peut comporter pas comporter d\'espace ni les caractères spéciaux suivant :
                    @ <, >, ", \''
    )]
    private ?string $pseudo = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private array $roles = [];

    // Variable qui ne sera pas enregistré en BDD
    private ?string $plainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank([
        'message' => 'Veuillez renseigner votre nom'
    ])]
    #[Assert\Length([
        'min' => '2',
        'max' => '50',
        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
        'maxMessage' => 'Votre prénom ne peut contenir plus de {{ limit }} caractères'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank([
        'message' => 'Veuillez renseigner votre prénom'
        ])]
    #[Assert\Length([
        'min' => '2',
        'max' => '50',
        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
        'maxMessage' => 'Votre prénom ne peut contenir plus de {{ limit }} caractères'])]
    private ?string $firstName = null;

    #[Assert\NotBlank([
        'message' => 'veuillez renseigner votre date de naissance'
    ])]
    #[Assert\LessThanOrEqual([
        'value' => '-18 years',
        'message' => 'Vous devez avoir plus de 18 ans pour vous inscrire!'])]
    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank([
        'message' => 'Veuillez renseigner une adresse'
    ])]
    #[Assert\Length([
        'min' => '2',
        'max' => '150',
        'minMessage' => 'Veuillez entrer une adresse valide',
        'maxMessage' => 'Veuillez entrer une adresse valide'
    ])]
    private ?string $adress = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank([
        'message' => 'veuillez renseigner un code postal'
    ])]
    #[Assert\Length(
        min:5,
        max: 5,
        exactMessage: 'Veuillez entrer un code postal valide, {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$/',
        message: 'Veuillez renseigner un code postal valide'
    )]
    private ?string $zipCode = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank([
        'message' => 'veuillez renseigner votre ville'
    ])]
    #[Assert\Length([
        'min' => '2',
        'max' => '50',
        'minMessage' => 'Veuillez entrer un nom de ville valide',
        'maxMessage' => 'Veuillez entrer un nom de ville valide'
    ])]
    private ?string $city = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank([
        'message' => 'veuillez renseigner un numéro de téléphone'
    ])]
    #[Assert\Regex(
        pattern: '/^0[0-7](\d{8})$/',
        message: 'Veuillez rentrer un numéro de téléphone valide'
    )]
    private ?string $tel = null;

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Assert\NotNull()]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class, cascade: ["remove"])]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    private Collection $reviews;

    #[ORM\Column(nullable: true)]
    private ?bool $ban = false;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $membershipContributionEndDate = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->orders = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of plainPassword
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set the value of plainPassword
     *
     * @return  self
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

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

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

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

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    public function isBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(?bool $ban): static
    {
        $this->ban = $ban;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeImmutable $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getMembershipContributionEndDate(): ?\DateTimeImmutable
    {
        return $this->membershipContributionEndDate;
    }

    public function setMembershipContributionEndDate(?\DateTimeImmutable $membershipContributionEndDate): static
    {
        $this->membershipContributionEndDate = $membershipContributionEndDate;

        return $this;
    }
}
