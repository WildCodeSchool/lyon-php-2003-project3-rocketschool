<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Faq;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FaqType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextType::class, ['empty_data' => ''])
            ->add('answer', TextareaType::class, [
                'empty_data' => '',
                'trim' => false,
            ])
            ->add('category', EntityType::class, [
                'placeholder' => 'Choisir la catégorie',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('image', FileType::class, [
                'label' => 'add an image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'L\'image doit être au format png, jpeg ou jpg'
                    ])
                ]
            ])
        ;

        $builder->get('answer')
            ->addModelTransformer(new CallbackTransformer(
            // transform <br/> to \n so the textarea reads easier
                function ($originalDescription) {
                    return preg_replace('#<br\s*/?>#i', "\n", $originalDescription);
                },
                function ($submittedDescription) {
                    // remove most HTML tags (but not br,p)
                    $cleaned = strip_tags($submittedDescription, '<br><br/><p>');

                    // transform any \n to real <br/>
                    return str_replace("\n", '<br/>', $cleaned);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Faq::class,
        ]);
    }
}
