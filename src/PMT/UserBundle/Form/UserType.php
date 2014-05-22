<?php

namespace PMT\UserBundle\Form;

use PMT\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('last_name')
            ->add('first_name')
            ->add('plain_password', 'password', array(
                'constraints' => array(new NotBlank(array('groups' => array('New'))))));

        if ($options['is_manager']) {
            $builder->add('role', 'choice', array('choices' => User::getRoleOptions(), 'empty_value' => ''));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PMT\UserBundle\Entity\User',
            'is_manager' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }
}
