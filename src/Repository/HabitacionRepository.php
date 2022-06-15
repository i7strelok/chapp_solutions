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
        //Solución fea
        $sql = "SELECT * FROM habitacion h WHERE h.capacidad>=$huespedes
        AND h.id NOT IN (SELECT rh.habitacion_id 
        FROM reserva_habitacion rh 
        INNER JOIN reserva r ON r.id = rh.reserva_id
        INNER JOIN habitacion_etiqueta eh ON eh.habitacion_id = h.id
        INNER JOIN etiqueta e ON e.id = eh.etiqueta_id
		WHERE ((e.descripcion LIKE '%".$etiquetas."%') OR (e.nombre LIKE '%".$etiquetas."%')) AND
		(r.fecha_inicio BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."') or
		(r.fecha_fin BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."')
		)";
        $em = $this->getEntityManager();
        return $em->getConnection()->fetchAllAssociative($sql);
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
