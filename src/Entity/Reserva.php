<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservaRepository::class)]
class Reserva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank()]
    private $fecha_inicio;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank()]
    private $fecha_fin;

    #[ORM\Column(type: 'smallint')]
    #[Assert\NotBlank()]
    private $numero_huespedes;

    #[ORM\ManyToOne(targetEntity: Cliente::class, inversedBy: 'reservas')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank()]
    private $cliente;

    #[ORM\ManyToMany(targetEntity: Habitacion::class, inversedBy: 'reservas')]
    #[Assert\NotBlank()]
    private $habitaciones;

    #[ORM\Column(type: 'string', length: 12)]
    #[Assert\NotBlank()]
    private $localizador;

    public function __construct()
    {
        $this->habitaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaInicio(): ?\DateTimeInterface
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio(\DateTimeInterface $fecha_inicio): self
    {
        $this->fecha_inicio = $fecha_inicio;

        return $this;
    }

    public function getFechaFin(): ?\DateTimeInterface
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(\DateTimeInterface $fecha_fin): self
    {
        $this->fecha_fin = $fecha_fin;

        return $this;
    }

    public function getNumeroHuespedes(): ?int
    {
        return $this->numero_huespedes;
    }

    public function setNumeroHuespedes(int $numero_huespedes): self
    {
        $this->numero_huespedes = $numero_huespedes;

        return $this;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * @return Collection<int, Habitacion>
     */
    public function getHabitaciones(): Collection
    {
        return $this->habitaciones;
    }

    public function addHabitacione(Habitacion $habitacione): self
    {
        if (!$this->habitaciones->contains($habitacione)) {
            $this->habitaciones[] = $habitacione;
        }

        return $this;
    }

    public function removeHabitacione(Habitacion $habitacione): self
    {
        $this->habitaciones->removeElement($habitacione);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName(); 
    }

    public function getLocalizador(): ?string
    {
        return $this->localizador;
    }

    public function setLocalizador(string $localizador): self
    {
        $this->localizador = $localizador;

        return $this;
    }
}
