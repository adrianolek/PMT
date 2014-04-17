<?php

namespace PMT\TrackingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TrackType extends AbstractType
{
    
    private $userId;
    public function __construct($userId)
    {
        $this->userId = $userId;
    }    
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userId = $this->userId;
        $builder
            ->add('task', 'entity', array('group_by' => 'projectName', 'empty_value' => '', 'class' => 'PMT\TaskBundle\Entity\Task', 'query_builder' => function($er) use ($userId){
                /* @var $er \PMT\TaskBundle\Entity\TaskRepository */
                $qb = $er->createQueryBuilder('t');
                $qb->join('t.assignedUsers', 'u');
                $qb->join('t.project', 'p');
                $qb->andWhere('u.id = :user_id');
                $qb->setParameter('user_id', $userId);
                $qb->addOrderBy('t.name');
                return $qb;
            }))
            ->add('date')
            ->add('startTime')
            ->add('endTime')
            ->add('description')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PMT\TrackingBundle\Entity\Track'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'track';
    }
}
