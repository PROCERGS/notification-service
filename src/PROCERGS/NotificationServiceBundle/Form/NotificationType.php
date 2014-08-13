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
                ->add('createdAt', 'datetime',
                        array('required' => false, 'widget' => 'single_text'))
                ->add('readDate', 'datetime',
                        array('required' => false, 'widget' => 'single_text'))
                ->add('isRead')
                ->add('level')
                ->add('receiver', 'collection',
                        array(
                    'type' => new ReceiverType(),
                    'allow_add' => true
                ))
                ->add('sender', 'collection',
                        array(
                    'type' => new SenderType(),
                    'allow_add' => true
                ))
                ->add('expireDate', 'datetime',
                        array('required' => false, 'widget' => 'single_text'))
                ->add('considerReadDate', 'datetime',
                        array('required' => false, 'widget' => 'single_text'))
                ->add('receivedDate', 'datetime',
                        array('required' => false, 'widget' => 'single_text'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PROCERGS\NotificationServiceBundle\Entity\Notification',
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }

}
