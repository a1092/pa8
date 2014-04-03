<?php

namespace Sf\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Sf\UserBundle\Entity\FoyerRepository;

class ChoiceType extends AbstractType
{
    private $userId;

    public function __construct($userId) {
        $this->userId=$userId;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userId = $this->userId;

        $builder->add('foyer', 'entity', array(
                    'class'    => 'SfUserBundle:Foyer',
                    'property' => 'name',
                    'query_builder' => function(FoyerRepository $r) use($userId) {
                        return $r->getSelectList($userId);},
                    'multiple' => false));
    }
    
    public function getName()
    {        
        return 'foyer_choice';
    }
}
