<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrestationRepository")
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
	 */
	private $name;

	/**
	 * @ORM\Column(type="integer")
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

	public function getPrice(): ?int {
		return $this->price;
	}

	public function setPrice( int $price ): self {
		$this->price = $price;

		return $this;
	}
}
