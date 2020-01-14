<?php

namespace App\Form;

use App\Entity\Item;
use App\Validator\ImgLinks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 200])]
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 5, 'max' => 1000])
                ]
            ])
            ->add('imgLinks', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new ImgLinks([
                        'min' => 1,
                        'max' => 3])
                ]
            ])
            ->add('price', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 12])
                ]
            ]);

        //Custom transformer:
//        $builder->get('imgLinks')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($imgLinksAsArray){
//                    return implode(',', $imgLinksAsArray);
//                },
//                function ($imgLinksAsString){
//                    return explode(',', $imgLinksAsString);
//                }
//            ))
//        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
