<?php
namespace Sf\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('file', 'file', array('label' => 'Image :'))
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array('data_class' => 'Sf\UserBundle\Entity\Image'));
  }

  public function getName()
  {
    return 'sf_userbundle_imagetype';
  }
}