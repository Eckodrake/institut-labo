<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CreationRepository")
 * @UniqueEntity("img")
 * @Vich\Uploadable()
 */
class Creation {
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
	private $img;

	/**
	 * @var File|null
	 * @Vich\UploadableField(mapping="creation_image", fileNameProperty="img")
	 */
	private $imageFile;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $updated_at;

	public function getId(): ?int {
		return $this->id;
	}

	public function getImg(): ?string {
		return $this->img;
	}

	public function setImg( ?string $img ): self {
		$this->img = $img;

		return $this;
	}

	public function getSlug(): string {
		return ( new Slugify() )->slugify( $this->img );
	}

	public function getUpdatedAt(): ?\DateTimeInterface {
		return $this->updated_at;
	}

	public function setUpdatedAt( \DateTimeInterface $updated_at ): self {
		$this->updated_at = $updated_at;

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
	 * @return Creation
	 */
	public function setImageFile( ?File $imageFile ): self {
		$this->imageFile = $imageFile;
		if ( $this->imageFile instanceof UploadedFile ) {
			$this->updated_at = new \DateTime( 'now' );
		}

		return $this;
	}
}
