<?php

namespace App\Form;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'attr' =>[
                    'placeholder' => 'Enter name product',
                ],
                'constraints' => [

                    new NotBlank(),
                    new Length([
                        'min' => 10
                    ]),
                    ],
                'required'=>false,

            ])
            ->add('price',NumberType::class,[
                'attr' => [
                    'placeholder' => 'Enter Price Product',
                    'title' => 'This is price of Product'
                ],
                'constraints'=> [
                    new NotBlank()
                ],
                'required'=>false,
            ])

            ->add('category',EntityType::class,[
                'class' => Category::class,
                 'required' => false,
                 'attr' => [
                    'class' => 'select2',
                 ],
                 'query_builder' => function(EntityRepository $er){
                return $er->createQueryBuilder('n')->orderBy('n.name','ASC');
                 }
            ])
            ->add('ADD',SubmitType::class,[
                'attr'=>[
                    'class'=>'btn btn-primary ',
                    'style'=>'margin-left : 300px'

                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
