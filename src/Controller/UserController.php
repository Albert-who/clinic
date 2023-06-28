<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\User;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser(); // Получаем текущего пользователя
        $userId = $user->getUserIdentifier(); 

        $query = $entityManager->createQueryBuilder()
            ->select('u.username')
            ->from('App\Entity\User', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();

        $username = $query->getSingleScalarResult();
        $selectedService = null;
        $DuserId = null;
        $freeDates = [];
        $lowestPrice = null;
        $allDates = [];

        $appointment = new Appointment();

        $form = $this->createForm(AppointmentType::class, $appointment);
       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedService = $form->get('service')->getData();

            $appointment->setUser($user);
            $appointment->setService($selectedService);
            
            // Получение значения DuserId из формы
            $DuserId = $form->get('DuserId')->getData();
            $userRepository = $entityManager->getRepository(User::class);
            $Duser = $userRepository->find($DuserId);
            $appointment->setDuser($Duser);


            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'freeDates' => $freeDates,
            'lowestPrice' => $lowestPrice,
            'selectedService' => $selectedService,
            'allDates' => $allDates,
            'username' => $username,
            
        ]);
    }

    /**
     * @Route("/get-available-dates", name="get_available_dates", methods={"POST"})
     */
    public function getAvailableDates(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);
        $serviceId = $data['service'] ?? null;

        if ($serviceId !== null) {
            $query = $entityManager->createQueryBuilder()
                ->select('u.id as userId')
                ->from('App\Entity\Price', 'p')
                ->join('p.DuserId', 'u')
                ->where('p.serviceId = :serviceId')
                ->andWhere('p.price = (
                    SELECT MIN(p2.price) 
                    FROM App\Entity\Price p2 
                    WHERE p2.serviceId = :serviceId
                )')
                ->setParameter('serviceId', $serviceId)
                ->getQuery();

            $user = $query->getSingleResult();
            $userId = $user['userId'];

            $query = $entityManager->createQueryBuilder()
                ->select('a.date')
                ->from('App\Entity\Appointment', 'a')
                ->where('a.Duser = :userId')
                ->setParameter('userId', $userId)
                ->getQuery();

            $unavailableDates = $query->getResult();

            // Получение текущей даты
            $currentDate = new \DateTime();
            $currentDate->setTime(0, 0, 0);

            // Фильтрация дат, оставляя только те, которые настоящие или будущие
            $unavailableDates = array_filter($unavailableDates, function ($date) use ($currentDate) {
                return $date['date'] >= $currentDate;
            });
    
            $unavailableDates = array_map(function ($date) {
                return $date['date']->format('Y-m-d');
            }, $unavailableDates);
    
    
            return new JsonResponse([
                'unavailableDates' => $unavailableDates,
                'DuserId' => $userId
            ]);
        
        } else {
            $userId = null;
            $unavailableDates = [];
        }

        return new JsonResponse([
            'unavailableDates' => $unavailableDates,
            'DuserId' => $userId
        ]);
    }

    /**
     * @Route("/get-lowest-price", name="get_lowest_price", methods={"POST"})
     */
    public function getLowestPrice(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);
        $serviceId = $data['service'] ?? null;

        if ($serviceId !== null) {
            $query = $entityManager->createQueryBuilder()
                ->select('MIN(p.price) as lowestPrice')
                ->from('App\Entity\Price', 'p')
                ->where('p.serviceId = :serviceId')
                ->setParameter('serviceId', $serviceId)
                ->getQuery();
    
            $lowestPrice = $query->getSingleScalarResult();
            if($lowestPrice == null){
                $lowestPrice = "Для уточнения цены обратитесь в регистратуру по номеру: 8-800-555-35-35";
            }
        } else {
            $lowestPrice = null;
        }

        return new JsonResponse(['lowestPrice' => $lowestPrice]);
    }

    
}
