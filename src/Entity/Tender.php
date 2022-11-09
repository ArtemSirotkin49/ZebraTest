<?php

namespace App\Entity;

use App\Repository\TenderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TenderRepository::class)]
class Tender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ext_code = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $date_update = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtCode(): ?int
    {
        return $this->ext_code;
    }

    public function setExtCode(int $ext_code): self
    {
        $this->ext_code = $ext_code;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateUpdate(): ?string
    {
        return $this->date_update;
    }

    public function setDateUpdate(string $date_update): self
    {
        $this->date_update = $date_update;

        return $this;
    }
}
