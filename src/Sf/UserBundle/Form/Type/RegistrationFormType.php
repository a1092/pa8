<?php

namespace Sf\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->add('firstname', 'text', array('label' => 'Prénom :'));
		$builder->add('lastname', 'text', array('label' => 'Nom :'));
        $builder->add('gender', 'choice',
            array('empty_value' => 'Indéterminé', 'label' => 'Sexe :', 
                'choices' => array('Féminin' => 'Féminin', 'Masculin' => 'Masculin')));
        $builder->add('image', new ImageType(), array('required' => false, 'label' => ' '));
        
        $builder->add('foyers', 'collection', array('type' => new FoyerType(), 'allow_add' => true, 'allow_delete' => true, 'label' => ' '));


    }

    public function getName()
    {
        return 'sf_user_registration';
    }
}