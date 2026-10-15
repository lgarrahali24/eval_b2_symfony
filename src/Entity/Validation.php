<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Validation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $Validé = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isValidé(): ?bool
    {
        return $this->Validé;
    }

    public function setValidé(bool $Validé): static
    {
        $this->Validé = $Validé;

        return $this;
    }
}
