<?php

namespace PMT\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username')
            ->add('_password', 'password')
            ->add('_remember_me', 'checkbox', array('required' => false));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return null;
    }
}
