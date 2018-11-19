<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact {
	/**
	 * @var string|null
	 * @Assert\NotBlank()
	 * @Assert\Length(min="3", max="255")
	 */
	private $name;

	/**
	 * @var string|null
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $email;

	/**
	 * @var string|null
	 * @Assert\NotBlank()
	 * @Assert\Length(min="10")
	 */
	private $message;

	/**
	 * @return null|string
	 */
	public function getName(): ?string {
		return $this->name;
	}

	/**
	 * @param null|string $name
	 */
	public function setName( ?string $name ): void {
		$this->name = $name;
	}

	/**
	 * @return null|string
	 */
	public function getEmail(): ?string {
		return $this->email;
	}

	/**
	 * @param null|string $email
	 */
	public function setEmail( ?string $email ): void {
		$this->email = $email;
	}

	/**
	 * @return null|string
	 */
	public function getMessage(): ?string {
		return $this->message;
	}

	/**
	 * @param null|string $message
	 */
	public function setMessage( ?string $message ): void {
		$this->message = $message;
	}
}