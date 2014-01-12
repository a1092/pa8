<?php

namespace Sf\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFoyerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));
        $builder->add('firstname', 'text', array('label' => 'Prénom :'));
		$builder->add('lastname', 'text', array('label' => 'Nom :'));
        $builder->add('gender', 'choice',
            array('empty_value' => 'Indéterminé', 'label' => 'Sexe :', 
                'choices' => array('Féminin' => 'Féminin', 'Masculin' => 'Masculin')));
        $builder->add('image', new ImageType(), array('required' => false, 'label' => ' '));

        $builder->add('foyers', 'collection', array('type' => new FoyerType(), 'allow_add' => false, 'allow_delete' => false, 'label' => ' '));
    }

    public function getName()
    {
        return 'sf_user_registration_membre';
    }
}