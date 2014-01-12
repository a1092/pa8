<?php

namespace Sf\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FoyerEditType extends FoyerType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array('data_class' => 'Sf\UserBundle\Entity\Foyer'));
  }

    public function getName()
    {
        return 'foyer_edit';
    }
}