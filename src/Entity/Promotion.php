<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PromotionRepository")
 * @UniqueEntity("name")
 */
class Promotion {

	CONST PROMO = [
		0 => 'month',
		1 => 'contest'
	];

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="integer")
	 * @Assert\Choice({0, 1})
	 */
	private $type;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\Length(min="5", max="255")
	 */
	private $name;

	/**
	 * @ORM\Column(type="text")
	 * @Assert\Length(min="10")
	 */
	private $description;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $price;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $datetime;

	public function getId(): ?int {
		return $this->id;
	}

	public function getType(): ?int {
		return $this->type;
	}

	public function getPromoType(): string {
		return self::PROMO[$this->type];
	}

	public function setType( int $type ): self {
		$this->type = $type;

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

	public function setPrice( ?int $price ): self {
		$this->price = $price;

		return $this;
	}

	public function getDatetime(): ?\DateTimeInterface {
		return $this->datetime;
	}

	public function setDatetime( \DateTimeInterface $datetime ): self {
		$this->datetime = $datetime;

		return $this;
	}
}
