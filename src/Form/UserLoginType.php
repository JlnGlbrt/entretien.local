<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class UserLoginType
 * @package App\Form
 */
class UserLoginType extends AbstractType
{
    /**
     * Méthode qui construit le formulaire de connexion
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, array('label' => "Pseudo *",))
            ->add('_password', PasswordType::class, array('label' => "Mot de passe *",))
            ->add('submit', SubmitType::class, array('label' => "Connexion"));
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

    /**
     * @return null|string
     */
    public function getBlockPrefix() {
        return null;
    }
}