<?php

namespace Sf\ShoppingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sf\UserBundle\Entity\UserRepository;
use \DateTime;

class ShoppingListType extends AbstractType
{

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
            ->add('deadline', 'datetime', array('required' => false, 'data' => new \DateTime('tomorrow')))
            ->add('private', 'checkbox', array(
				'required' => false
			))
			->add('users', 'entity', array(
					'required' => false,
                    'class'    => 'SfUserBundle:User',
                    'property' => 'firstname',
                    'query_builder' => function(UserRepository $r) use($foyerId) {
                        return $r->getSelectList($foyerId);},
                    'multiple' => true))
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
