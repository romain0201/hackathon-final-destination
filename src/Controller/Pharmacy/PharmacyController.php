<?php

namespace App\Controller\Pharmacy;

use App\Entity\Order;
use App\Form\CommentType;
use App\Repository\OrderRepository;
use App\Service\TwilioService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PharmacyController extends AbstractController
{
    private TwilioService $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    #[Route('/pharmacie', name: 'app_pharmacy')]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        if (!$user || !in_array('ROLE_PHARMACY', $user->getRoles(), true)) {
            throw new AccessDeniedException('Access Denied. You do not have the necessary permissions to access this page.');
        }
        $orders = $orderRepository->findBy(["pharmacy" => $user->getId()]);
        return $this->render('back/pharmacy/index.html.twig', [
            "orders" => $orders
        ]);
    }

    #[Route('/pharmacie/ordonnance/{id}', name: 'app_pharmacy_order')]
    public function order(Order $order, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            try {
                $this->twilioService->sendSms($order->getClient()->getPhone(), $order->getComment());
            } catch (\Exception $e) {
                return new Response("Error: " . $e->getMessage());
            }

            return $this->redirectToRoute('app_pharmacy_order', ['id' => $order->getId()]);
        }

        return $this->render('back/pharmacy/order.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pharmacie/ordonnance/{id}/validate', name: 'app_pharmacy_order_validate')]
    public function orderValidate(Order $order, OrderRepository $orderRepository,  EntityManagerInterface $entityManager): Response
    {
        $order = $orderRepository->find($order);
        $order->setActive(false);
        $entityManager->persist($order);
        $entityManager->flush();

        try {
            $body = "Votre commande est en attente de récupération dans votre pharmacie.";
            $this->twilioService->sendSms($order->getClient()->getPhone(), $body);
        } catch (\Exception $e) {
            return new Response("Error: " . $e->getMessage());
        }

        return $this->redirectToRoute('app_pharmacy_order', ['id' => $order->getId()]);
    }
}
