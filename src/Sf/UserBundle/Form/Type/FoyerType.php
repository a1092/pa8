<?php

namespace Sf\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FoyerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', 'text', array('required' => false, 'label' => 'Nom :'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array('data_class' => 'Sf\UserBundle\Entity\Foyer'));
  }

    public function getName()
    {
        return 'foyer';
    }
}