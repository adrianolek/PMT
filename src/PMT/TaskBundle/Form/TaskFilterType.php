<?php

namespace PMT\TaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PMT\TaskBundle\Entity\Task;
use Doctrine\ORM\EntityManager;

class TaskFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('assignment', 'choice', array('choices' => array(
                'all' => 'Show all', 'assigned' => 'Show assigned to me', 'unassigned' => 'Show unassigned')))
            ->add('categories', 'choice', array('multiple' => true, 'choices' => Task::getCategoryOptions()))
            ->add('statuses', 'choice', array('multiple' => true, 'choices' => Task::getStatusOptions()))
            ->add('order', 'choice', array('choices' => array('priority' => 'Sort by priority',
                'date' => 'Sort by date', 'progress' => 'Sort by progress', 'name' => 'Sort by name')))
            ->add('date_start')
            ->add('date_end')
            ->add('filter', 'submit');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return null;
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
      $resolver->setDefaults(array(
          'csrf_protection' => false,
      ));
    }
}
