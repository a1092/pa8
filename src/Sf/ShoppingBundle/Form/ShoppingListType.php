<?php

namespace Sf\ShoppingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \DateTime;

class ShoppingListType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('deadline', 'datetime', array('required' => false, 'data' => new \DateTime('tomorrow')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sf\ShoppingBundle\Entity\ShoppingList'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sf_shoppingbundle_shoppinglist';
    }
}
