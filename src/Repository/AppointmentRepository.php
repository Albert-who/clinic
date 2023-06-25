<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function findByDate(Service $selectedService, int $selectedDoctorId): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a.date
            FROM App\Entity\Appointment a
            WHERE a.doctorId = :doctorId
            AND a.service = :service
            AND a.date NOT IN (
                SELECT b.date
                FROM App\Entity\Appointment b
                WHERE b.doctorId = :doctorId
                AND b.service = :service
            )'
        )
            ->setParameter('doctorId', $selectedDoctorId)
            ->setParameter('service', $selectedService);

        $freeDates = $query->getResult();

        // Преобразование результата в массив объектов DateTime
        $formattedDates = [];
        foreach ($freeDates as $date) {
            $formattedDates[] = $date['date'];
        }

        return $formattedDates;
    }
}
