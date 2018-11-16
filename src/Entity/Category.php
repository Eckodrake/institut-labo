<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @Vich\Uploadable()
 */
class Category {
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @var File|null
	 * @Vich\UploadableField(mapping="category_image", fileNameProperty="image")
	 */
	private $imageFile;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255)
	 */
	private $image;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Prestation", mappedBy="category")
	 */
	private $prestations;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $updated_at;

	public function __construct() {
		$this->prestations = new ArrayCollection();
	}

	public function getId(): ?int {
		return $this->id;
	}

	public function getName(): ?string {
		return $this->name;
	}

	public function setName( string $name ): self {
		$this->name = $name;

		return $this;
	}

	public function getSlug(): string {
		return ( new Slugify() )->slugify( $this->name );
	}

	public function getImage(): ?string {
		return $this->image;
	}

	public function setImage(?string $image = null): self {
		$this->image = $image;

		return $this;
	}

	/**
	 * @return Collection|Prestation[]
	 */
	public function getPrestations(): Collection {
		return $this->prestations;
	}

	public function addPrestation( Prestation $prestation ): self {
		if ( ! $this->prestations->contains( $prestation ) ) {
			$this->prestations[] = $prestation;
			$prestation->setCategory( $this );
		}

		return $this;
	}

	public function removePrestation( Prestation $prestation ): self {
		if ( $this->prestations->contains( $prestation ) ) {
			$this->prestations->removeElement( $prestation );
			// set the owning side to null (unless already changed)
			if ( $prestation->getCategory() === $this ) {
				$prestation->setCategory( null );
			}
		}

		return $this;
	}

	/**
	 * @return null|File
	 */
	public function getImageFile(): ?File {
		return $this->imageFile;
	}

	/**
	 * @param null|File $imageFile
	 *
	 * @return Category
	 */
	public function setImageFile( ?File $imageFile ): self {
		$this->imageFile = $imageFile;
		if ( $this->imageFile instanceof UploadedFile ) {
			$this->updated_at = new \DateTime( 'now' );
		}

		return $this;
	}

	public function getUpdatedAt(): ?\DateTimeInterface {
		return $this->updated_at;
	}

	public function setUpdatedAt( \DateTimeInterface $updated_at ): self {
		$this->updated_at = $updated_at;

		return $this;
	}
}
