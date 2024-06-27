<?php

namespace App\Form;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('created_at', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),  // Définit la valeur par défaut à la date actuelle
            ])
            ->add('preparation_date', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),  // Définit la valeur par défaut à la date actuelle
            ])
            ->add('orderItems', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('file', FileType::class, [
                'label' => 'Upload File',
                'mapped' => false,
                'required' => false,
            ])
            ->add('client', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('pharmacy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
