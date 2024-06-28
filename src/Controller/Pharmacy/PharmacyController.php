<?php

namespace App\Controller\Pharmacy;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function order(Order $order): Response
    {
//        dd($order);
        return $this->render('back/pharmacy/order.html.twig', [
            'controller_name' => 'PharmacyController',
            "order"=>$order
        ]);
    }
}
