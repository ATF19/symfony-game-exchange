<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		// Generer le formulaire d'inscription
        $builder->add('cin', 'text', array( 
            'attr'   =>  array(
                'class'   => 'form-control')
            )
        )
		->add('nom', 'text', array( 
            'attr'   =>  array(
                'class'   => 'form-control')
            ))
		->add('prenom', 'text', array( 
            'attr'   =>  array(
                'class'   => 'form-control')
            ))
		->add('email', 'email', array( 
            'attr'   =>  array(
                'class'   => 'form-control')
            ))
		->add('password', 'password', array( 
            'attr'   =>  array(
                'class'   => 'form-control')
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Utilisateur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_utilisateur';
    }


}
