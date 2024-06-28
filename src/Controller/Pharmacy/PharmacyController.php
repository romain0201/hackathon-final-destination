<?php

namespace App\Controller\Pharmacy;

use App\Entity\Order;
use App\Form\CommentType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PharmacyController extends AbstractController
{
    #[Route('/pharmacie', name: 'app_pharmacy')]
    public function index(OrderRepository $orderRepository): Response
    {

        $user = $this->getUser();
        if (!$user || !in_array('ROLE_PHARMACY', $user->getRoles(), true)) {
            throw new AccessDeniedException('Access Denied. You do not have the necessary permissions to access this page.');
        }
        $orders = $orderRepository->findBy(["pharmacy"=>$user->getId()]);
        return $this->render('back/pharmacy/index.html.twig', [
            'controller_name' => 'PharmacyController',
            "orders"=>$orders
        ]);
    }
    #[Route('/pharmacie/order/{id}', name: 'app_pharmacy_order')]
    public function order(Order $order, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $order);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('success', 'Commentaire mis à jour avec succès.');
            return $this->redirectToRoute('app_pharmacy_order', ['id' => $order->getId()]);
        }

        return $this->render('back/pharmacy/order.html.twig', [
            'controller_name' => 'PharmacyController',
            'order' => $order,
            'form' => $form->createView(),
        ]);
    }
}
