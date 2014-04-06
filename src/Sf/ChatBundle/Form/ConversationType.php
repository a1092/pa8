<?php

namespace Sf\ChatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Sf\UserBundle\Entity\UserRepository;

class ConversationType extends AbstractType
{
    private $foyerId;

    public function __construct($foyerId) {
        $this->foyerId=$foyerId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $foyerId = $this->foyerId;

        $builder->add('users', 'entity', array(
                    'class'    => 'SfUserBundle:User',
                    'property' => 'firstname',
                    'query_builder' => function(UserRepository $r) use($foyerId) {
                        return $r->getSelectList($foyerId);},
                    'multiple' => true));
    }
    
    public function getName()
    {        
        return 'conversation';
    }
}
