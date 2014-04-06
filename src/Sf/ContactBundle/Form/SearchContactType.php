<?php

namespace Sf\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('recherche', 'text');
    }
    
    public function getName()
    {        
        return 'searchcontact';
    }
}
