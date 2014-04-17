<?php

namespace PMT\TaskBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use PMT\TaskBundle\Entity\Task;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
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
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PMT\TaskBundle\Entity\Task',
            'user_repository' => null,
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
