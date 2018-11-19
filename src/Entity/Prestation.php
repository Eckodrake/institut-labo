<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrestationRepository")
 * @UniqueEntity("name")
 */
class Prestation {
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="prestations")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $category;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\Length(min="3", max="255")
	 */
	private $name;

	/**
	 * @ORM\Column(type="integer")
	 * @Assert\Range(min="0")
	 */
	private $price;

	public function getId(): ?int {
		return $this->id;
	}

	public function getCategory(): ?Category {
		return $this->category;
	}

	public function setCategory( ?Category $category ): self {
		$this->category = $category;

		return $this;
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

	public function getPrice(): ?int {
		return $this->price;
	}

	public function setPrice( int $price ): self {
		$this->price = $price;

		return $this;
	}
}
