<?php

namespace PROCERGS\NotificationServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NotificationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('icon')
            ->add('title')
            ->add('shortText')
            ->add('text')
            ->add('callbackUrl')
            ->add('createdAt')
            ->add('readDate')
            ->add('isRead')
            ->add('level')
            ->add('receiver')
            ->add('sender')
            ->add('expireDate')
            ->add('considerReadDate')
            ->add('receivedDate')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PROCERGS\NotificationServiceBundle\Entity\Notification'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'procergs_notificationservicebundle_notification';
    }
}
