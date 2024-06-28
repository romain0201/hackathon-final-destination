<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $pharmacies = $this->userRepository->findByRole('ROLE_PHARMACY');
        $clients = $this->userRepository->findByRole('ROLE_PATIENT');

        $builder
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            ->add('preparation_date', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            ->add('orderItems', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('doctor')
            ->add('file', FileType::class, [
                'label' => 'Upload File',
                'mapped' => false,
                'required' => false,
            ])
            ->add('client', EntityType::class, [
                'class' => User::class,
                'choices' => $clients,
                'choice_label' => 'email',
            ])
            ->add('pharmacy', EntityType::class, [
                'class' => User::class,
                'choices' => $pharmacies,
                'choice_label' => 'email',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
