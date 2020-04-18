<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Net2GridRepository")
 */
class Net2Grid
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $gatewaiUi;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $profile;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $endpoint;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $cluster;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $attribute;

    /**
     * @ORM\Column(type="bigint")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="bigint")
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGatewaiUi(): ?string
    {
        return $this->gatewaiUi;
    }

    public function setGatewaiUi(string $gatewaiUi): self
    {
        $this->gatewaiUi = $gatewaiUi;

        return $this;
    }

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getCluster(): ?string
    {
        return $this->cluster;
    }

    public function setCluster(string $cluster): self
    {
        $this->cluster = $cluster;

        return $this;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }
}
