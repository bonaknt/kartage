<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class UsersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
        ->add('username', TextType::class, array(
			'label' => 'Nom d\'utilisateur',
			'attr'	=> ['class' => 'form-control'],
		))
        ->add('email', EmailType::class, array(
			'label' => 'Email',
			'attr'	=> ['class' => 'form-control'],
		))
        ->add('plainPassword', RepeatedType::class, array(
			'type' => PasswordType::class,
			'first_options'  => array(
				'label' => 'Mot de passe',
				'attr'	=> ['class' => 'form-control'],
				),
			'second_options' => array(
				'label' => 'Repéter le mot de passe',
				'attr'	=> ['class' => 'form-control'],
				),
		))

        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Users'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_users';
    }


}
