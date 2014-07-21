<?php

namespace PMT\TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PMT\TaskBundle\Entity\Task;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = $options['user_repository']->getAssignedUsersChoices($options['data']);

        /* @var $builder \Symfony\Component\Form\FormBuilder */
        $builder->add('name')
            ->add('description')
            ->add('category', 'choice', array('choices' => Task::getCategoryOptions(),
                'empty_value' => '',
            ))
            ->add('estimatedTimeHours', 'text', array('label' => 'Estimated time'))
            ->add('status', 'choice', array('choices' => Task::getStatusOptions()))
            ->add('progress')
            ->add('assignedUsers', 'entity', array(
                'class' => 'PMTUserBundle:User',
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ));

        if ($options['new']) {
            $builder->add('priority', 'choice', array(
                'choices' => array(
                    100 => 'very high',
                    75 => 'high',
                    50 => 'normal',
                    25 => 'low',
                    0 => 'very low',
            )));
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PMT\TaskBundle\Entity\Task',
            'user_repository' => null,
            'new' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'task';
    }
}
