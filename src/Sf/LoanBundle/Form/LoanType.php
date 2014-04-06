<?php

namespace Sf\LoanBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sf\UserBundle\Entity\UserRepository;

class LoanType extends AbstractType
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
            ->add('lender', 'entity', array(
                    'class'    => 'SfUserBundle:User',
                    'property' => 'firstname',
                    'query_builder' => function(UserRepository $r) use($foyerId) {
                        return $r->getSelectList($foyerId);},
                    'multiple' => false))
            ->add('item')
            ->add('borrower', 'entity', array(
                    'class'    => 'SfUserBundle:User',
                    'property' => 'firstname',
                    'query_builder' => function(UserRepository $r) use($foyerId) {
                        return $r->getSelectList($foyerId);},
                    'multiple' => false)
);
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sf\LoanBundle\Entity\Loan'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sf_loanbundle_loan';
    }
}
