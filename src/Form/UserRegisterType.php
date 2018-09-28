<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class UserRegisterType
 * @package App\Form
 */
class UserRegisterType extends AbstractType
{
    /**
     * Méthode qui construit le formulaire d'inscription
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, array('label' => 'Prénom *'))
            ->add('lastName', TextType::class, array('label' => 'Nom *'))
            ->add('username', TextType::class, array('label' => 'Pseudo *'))
            ->add('email', EmailType::class, array('label' => 'Email *'))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe *'),
                'second_options' => array('label' => 'Confirmez le mot de passe *'),
            ))
            ->add('submit', SubmitType::class, array('label' => "Inscription"));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}