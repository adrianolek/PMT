<?php

namespace PMT\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['user_repository']) {
            $choices = $options['user_repository']->getAssignedUsersChoices($options['data']);
        }

        $builder->add('name')
            ->add('assignedUsers', 'entity', array(
                'class' => 'PMTUserBundle:User',
                'multiple' => true,
                'expanded' => true,
                'choices' => isset($choices) ? $choices : null,
            ));;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PMT\ProjectBundle\Entity\Project',
            'user_repository' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'project';
    }
}
