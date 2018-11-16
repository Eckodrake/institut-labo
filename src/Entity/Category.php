<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
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
	 * @ORM\Column(type="string", length=255)
	 */
	private $image;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Prestation", mappedBy="category")
	 */
	private $prestations;

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

	public function setImage( string $image ): self {
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
}
