<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @Vich\Uploadable()
 * @UniqueEntity("name")
 */
class Product {
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\Length(min="3", max="255")
	 */
	private $name;

	/**
	 * @var File|null
	 * @Vich\UploadableField(mapping="product_image", fileNameProperty="img")
	 */
	private $imageFile;

	/**
	 * @ORM\Column(type="text")
	 * @Assert\Length(min="10")
	 */
	private $description;

	/**
	 * @ORM\Column(type="integer")
	 * @Assert\Range(min="0")
	 */
	private $price;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255)
	 */
	private $img;

	/**
	 * @ORM\Column(type="datetime")
	 * @Assert\DateTime()
	 */
	private $update_at;

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

	public function getDescription(): ?string {
		return $this->description;
	}

	public function setDescription( string $description ): self {
		$this->description = $description;

		return $this;
	}

	public function getPrice(): ?int {
		return $this->price;
	}

	public function setPrice( int $price ): self {
		$this->price = $price;

		return $this;
	}

	public function getImg(): ?string {
		return $this->img;
	}

	public function setImg( string $img = null ): self {
		$this->img = $img;

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
	 * @return Product
	 */
	public function setImageFile( ?File $imageFile ): self {
		$this->imageFile = $imageFile;
		if ( $this->imageFile instanceof UploadedFile ) {
			$this->update_at = new \DateTime( 'now' );
		}

		return $this;
	}

	public function getUpdateAt(): ?\DateTimeInterface {
		return $this->update_at;
	}

	public function setUpdateAt( \DateTimeInterface $update_at ): self {
		$this->update_at = $update_at;

		return $this;
	}
}
