<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The passwords must match',
                'required' => true,
                'first_options' => [
                    'label' => 'Password'
                ],
                'second_options' => [
                    'label' => 'Repeat Password'
                ]
            ])
            ->add('first_name', TextType::class, [
                'label' => 'First Name',
                'required' => true,
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('street_address', TextType::class, [
                'label' => 'Street Address',
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'required' => true,
            ])
            ->add('country', TextType::class, [
                'label' => 'Country',
                'required' => true,
            ])
//            ->add('is_active', HiddenType::class, [
//                'data' => true,
//            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-md btn-dark',
                    'value' => 'submit'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
