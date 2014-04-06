<?php

namespace Sf\TodoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sf\UserBundle\Entity\UserRepository;

class TaskType extends AbstractType
{
    private $foyerId;

    public function __construct($foyerId) {
        $this->foyerId=$foyerId;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $foyerId = $this->foyerId;

        $builder
            ->add('name')
            ->add('description')
            ->add('deadline', 'datetime', array('data' => new \DateTime('now')))
            ->add('users', 'entity', array(
                    'class'    => 'SfUserBundle:User',
                    'property' => 'firstname',
                    'query_builder' => function(UserRepository $r) use($foyerId) {
                        return $r->getSelectList($foyerId);},
                    'multiple' => true)
);
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sf\TodoBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sf_todobundle_task';
    }
}
