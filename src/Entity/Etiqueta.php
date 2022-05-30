<?php

namespace App\Entity;

use App\Repository\EtiquetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtiquetaRepository::class)]
class Etiqueta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 60)]
    private $nombre;

    #[ORM\Column(type: 'string', length: 255)]
    private $descripcion;

    #[ORM\ManyToMany(targetEntity: Habitacion::class, mappedBy: 'etiquetas')]
    private $habitaciones;

    public function __construct()
    {
        $this->habitaciones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

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
            $habitacione->addEtiqueta($this);
        }

        return $this;
    }

    public function removeHabitacione(Habitacion $habitacione): self
    {
        if ($this->habitaciones->removeElement($habitacione)) {
            $habitacione->removeEtiqueta($this);
        }

        return $this;
    }
}
