<?php


namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\HttpFoundation\Request;


class TaskType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->add('name', TextType::class)
            ->add('dodate',DateTimeType::class)
            ->add('category', TextType::class)
            ->add('description',TextareaType::class)
            ->add('user',HiddenType::class,array('data'=>'ad        '))
            ->add('save',SubmitType::class)
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Task'
        ]);
    }
}


