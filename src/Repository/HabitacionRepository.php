<?php

namespace App\Repository;

use App\Entity\Habitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habitacion>
 *
 * @method Habitacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habitacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habitacion[]    findAll()
 * @method Habitacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habitacion::class);
    }

    public function add(Habitacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Habitacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllRooms(){
        return $this->getEntityManager()
        ->createQuery('
            SELECT habitacion.id, habitacion.capacidad, habitacion.precio_diario, habitacion.descripcion, habitacion.numero
            FROM App:Habitacion habitacion
        ');
        /*return $this->createQueryBuilder('h')
        ->orderBy('h.precio_diario', 'ASC')->getQuery();*/
    }

    public function getAvailableRooms($fecha_inicio, $fecha_fin, $huespedes, $etiquetas){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $sub = $em->createQueryBuilder();
        $orX = $sub->expr()->orX();
        $orX->add($qb->expr()->between('r.fecha_inicio ', ':fecha_inicio', ':fecha_fin'));
        $orX->add($qb->expr()->between('r.fecha_fin', ':fecha_inicio', ':fecha_fin'));
        $orX->add($qb->expr()->between(':fecha_inicio', 'r.fecha_inicio', 'r.fecha_fin'));
        $sub->select('IDENTITY(r.habitacion)')
        ->from('App:Reserva', 'r') 
        ->where($orX);
          //->where($qb->expr()->eq('arl.asset_id',1));
        $qb->select("h")
        ->from('App:Habitacion','h')
        ->innerJoin('h.reservas','rh')
        ->innerJoin('h.etiquetas','e')
        ->where($qb->expr()->like('e.nombre', ':etiquetas'))   
        ->orWhere($qb->expr()->like('e.descripcion', ':etiquetas'))
        ->orWhere($qb->expr()->like('h.descripcion', ':etiquetas'))
        ->Andwhere('h.capacidad >= :capacidad')
        ->Andwhere($qb->expr()->notIn('h.id',  $sub->getDQL()))
        ->setParameters(['capacidad' => $huespedes, 'etiquetas' => '%'.$etiquetas.'%', 
        'fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin]);

        return $qb->getQuery();
    }
//    /**
//     * @return Habitacion[] Returns an array of Habitacion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Habitacion
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
