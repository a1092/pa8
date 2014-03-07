<?php

namespace Sf\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email', 'text', array('required' => false))
            ->add('address', 'textarea', array('required' => false))
            ->add('homePhoneNumber', 'text', array('required' => false))
            ->add('mobilePhoneNumber', 'text', array('required' => false))
            ->add('otherPhoneNumber', 'text', array('required' => false))
            ->add('category', 'text', array('required' => false))
            ->add('remark', 'textarea', array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sf\ContactBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sf_contactbundle_contact';
    }
}
