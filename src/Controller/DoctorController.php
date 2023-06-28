<?php

namespace App\Controller;

use App\Entity\DoctorToService;
use App\Entity\Price;
use App\Entity\Service;
use App\Entity\Appointment;
use App\Form\AddServiceFormType;
use App\Form\DeleteServiceFormType;
use App\Form\SetPriceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/doctor", name="app_doctor")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $addServiceForm = $this->createForm(AddServiceFormType::class);
        $addServiceForm->handleRequest($request);

        $setPriceForm = $this->createForm(SetPriceFormType::class);
        $setPriceForm->handleRequest($request);

        $deleteServiceForm = $this->createForm(DeleteServiceFormType::class);
        $deleteServiceForm->handleRequest($request);

        $user = $this->getUser(); // Получаем текущего пользователя
        $userId = $user->getUserIdentifier(); 

        $query = $entityManager->createQueryBuilder()
            ->select('u.username')
            ->from('App\Entity\User', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery();

        $username = $query->getSingleScalarResult();
        

        if ($addServiceForm->isSubmitted() && $addServiceForm->isValid()) {
            $service = new Service(); // Создание новой сущности Service
            $serviceName = $addServiceForm->get('name')->getData();

            $service->setName($serviceName); // Установка имени услуги

            $this->entityManager->persist($service);
            $this->entityManager->flush();

            $this->addFlash('success', 'Новая услуга успешно добавлена.');

            return $this->redirectToRoute('app_doctor');
        }

        if ($setPriceForm->isSubmitted() && $setPriceForm->isValid()) {
            $selectedService = $setPriceForm->get('service')->getData();
            $priceValue = $setPriceForm->get('price')->getData();

            // Создание новой записи DoctorToService
            $doctorToService = new DoctorToService();
            $doctorToService->setDoctor($this->getUser()); // Установка текущего пользователя (доктора)
            $doctorToService->setService($selectedService);

            $this->entityManager->persist($doctorToService);
            $this->entityManager->flush();

            // Создание новой записи Price
            $price = new Price();
            $price->setDoctor($this->getUser()); // Установка текущего пользователя (доктора)
            $price->setService($selectedService);
            $price->setPrice($priceValue);

            $this->entityManager->persist($price);
            $this->entityManager->flush();

            $this->addFlash('success', 'Цена для услуги успешно установлена.');

            return $this->redirectToRoute('app_doctor');
        }

        if ($deleteServiceForm->isSubmitted() && $deleteServiceForm->isValid()) {
            $selectedService = $deleteServiceForm->get('service')->getData();
    
            $appointmentRepository = $this->entityManager->getRepository(Appointment::class);
            $priceRepository = $this->entityManager->getRepository(Price::class);
        
            // Проверка наличия записей в таблице Appointment
            $appointments = $appointmentRepository->findBy(['serviceId' => $selectedService]);
        
            if (!empty($appointments)) {
                $this->addFlash('error', 'Услуга не может быть удалена, так как пациенты записанные на эту услугу.');
            } else {
                // Проверка наличия записей в таблице Price
                $prices = $priceRepository->findBy(['serviceId' => $selectedService]);
        
                if (!empty($prices)) {
                    foreach ($prices as $price) {
                        $this->entityManager->remove($price);
                    }
                    $this->entityManager->flush();
                }
        
                // Удаление записей в таблице DoctorToService
                $doctorToServiceRepository = $this->entityManager->getRepository(DoctorToService::class);
                $doctorToServices = $doctorToServiceRepository->findBy(['serviceId' => $selectedService]);
        
                foreach ($doctorToServices as $doctorToService) {
                    $this->entityManager->remove($doctorToService);
                }
                $this->entityManager->flush();
        
                // Удаление записи в таблице Service
                $this->entityManager->remove($selectedService);
                $this->entityManager->flush();
        
                $this->addFlash('success', 'Услуга успешно удалена.');
            }
        
            return $this->redirectToRoute('app_doctor');
        }

        return $this->render('doctor/index.html.twig', [
            'addServiceForm' => $addServiceForm->createView(),
            'setPriceForm' => $setPriceForm->createView(),
            'deleteServiceForm' => $deleteServiceForm->createView(),
            'username' => $username,
        ]);
    }
}
