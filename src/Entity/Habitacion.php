<?php

namespace App\Entity;

use App\Repository\HabitacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HabitacionRepository::class)]
class Habitacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'smallint')]
    #[Assert\NotBlank()]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'El Nº de la habitación debe ser superior o igual a 1')]
    #[Assert\LessThanOrEqual(value: 1000, message: 'El Nº de la habitación indicado es demasiado grande')]
    private $numero;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank()]
    private $descripcion;

    #[ORM\Column(type: 'smallint')]
    #[Assert\NotBlank()]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'La capacidad debe ser superior o igual a 1')]
    #[Assert\LessThanOrEqual(value: 4, message: 'La capacidad no puede ser superior a 4')]
    private $capacidad;

    #[ORM\Column(type: 'float', precision: 5, scale: 2)]
    #[Assert\NotBlank()]
    private $precio_diario;

    #[ORM\ManyToMany(targetEntity: Etiqueta::class, inversedBy: 'habitaciones')]
    private $etiquetas;

    #[ORM\OneToMany(mappedBy: 'habitacion', targetEntity: Reserva::class)]
    private $reservas;

    public function __construct()
    {
        $this->etiquetas = new ArrayCollection();
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCapacidad(): ?int
    {
        return $this->capacidad;
    }

    public function setCapacidad(int $capacidad): self
    {
        $this->capacidad = $capacidad;

        return $this;
    }

    public function getPrecioDiario(): ?int
    {
        return $this->precio_diario;
    }

    public function setPrecioDiario(int $precio_diario): self
    {
        $this->precio_diario = $precio_diario;

        return $this;
    }

    /**
     * @return Collection<int, Etiqueta>
     */
    public function getEtiquetas(): Collection
    {
        return $this->etiquetas;
    }

    public function addEtiqueta(Etiqueta $etiqueta): self
    {
        if (!$this->etiquetas->contains($etiqueta)) {
            $this->etiquetas[] = $etiqueta;
        }

        return $this;
    }

    public function removeEtiqueta(Etiqueta $etiqueta): self
    {
        $this->etiquetas->removeElement($etiqueta);

        return $this;
    }
    
    public function __toString(): string
    {
        return $this->getDescripcion(); 
    }

    /**
     * @return Collection<int, Reserva>
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas[] = $reserva;
            $reserva->setHabitacion($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getHabitacion() === $this) {
                $reserva->setHabitacion(null);
            }
        }

        return $this;
    }
}
