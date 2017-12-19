<?php


namespace AppBundle\Form\Type;
use AppBundle\Entity\Category;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\HttpFoundation\Request;


class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->add('title', TextType::class)
            ->add('category', EntityType::class, array('class' => 'AppBundle:Category',


    'choice_label' => 'name'))
            ->add('description',TextareaType::class)
            ->add('image' , FileType::class, array('label' => 'Image'))
            ->add('save',SubmitType::class)

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Product'
        ]);
    }
}


