<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\SousCategorieUploadController;
use App\Repository\SousCategorieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: SousCategorieRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    collectionOperations: [


        'post' => [

            'controller'=>SousCategorieUploadController::class,
            'deserialize' => false

        ],
        'get' => [
            'path' => '/sous_categories/',

        ],

    ]

)]class SousCategorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 500)]

    protected ?string $image = null;

    #[Vich\UploadableField(mapping: 'sous_categorie', fileNameProperty: 'image')]

    private ?File $imageFile = null;


    #[ORM\ManyToOne(inversedBy: 'sousCategorie')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $imageFile|null $imageFile

     */
    public function setImageFile( ?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {

            $this->updatedAt = new \DateTimeImmutable();
        }

    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage( $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
