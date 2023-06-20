<?php

namespace App\Controller;

use App\Entity\DoctorToService;
use App\Entity\Price;
use App\Form\ServiceFormType;
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
    public function addPrice(Request $request): Response
    {
        $form = $this->createForm(ServiceFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedService = $form->get('service')->getData();
            $priceValue = $form->get('price')->getData();

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

            $this->addFlash('success', 'Услуга успешно добавлена.');

            return $this->redirectToRoute('app_doctor');
        }

        return $this->render('doctor/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
