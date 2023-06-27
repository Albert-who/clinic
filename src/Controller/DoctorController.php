<?php

namespace App\Controller;

use App\Entity\DoctorToService;
use App\Entity\Price;
use App\Entity\Service;
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
    public function index(Request $request): Response
    {
        $addServiceForm = $this->createForm(AddServiceFormType::class);
        $addServiceForm->handleRequest($request);

        $setPriceForm = $this->createForm(SetPriceFormType::class);
        $setPriceForm->handleRequest($request);

        $deleteServiceForm = $this->createForm(DeleteServiceFormType::class);
        $deleteServiceForm->handleRequest($request);

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

            $doctorToService = new DoctorToService(); // Создание новой сущности DoctorToService
            $price = new Price(); // Создание новой сущности Price

            $doctorToService->setService($selectedService); // Установка выбранной услуги
            $doctorToService->setDoctor($this->getUser()); // Установка текущего пользователя как доктора

            $price->setService($selectedService); // Установка выбранной услуги
            $price->setPrice($priceValue); // Установка цены

            $this->entityManager->persist($doctorToService);
            $this->entityManager->persist($price);
            $this->entityManager->flush();

            $this->addFlash('success', 'Цена для услуги успешно установлена.');

            return $this->redirectToRoute('app_doctor');
        }

        if ($deleteServiceForm->isSubmitted() && $deleteServiceForm->isValid()) {
            $selectedServices = $deleteServiceForm->get('service')->getData();

            foreach ($selectedServices as $selectedService) {
                $this->entityManager->remove($selectedService);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Выбранные услуги успешно удалены.');

            return $this->redirectToRoute('app_doctor');
        }

        return $this->render('doctor/index.html.twig', [
            'addServiceForm' => $addServiceForm->createView(),
            'setPriceForm' => $setPriceForm->createView(),
            'deleteServiceForm' => $deleteServiceForm->createView(),
        ]);
    }
}
