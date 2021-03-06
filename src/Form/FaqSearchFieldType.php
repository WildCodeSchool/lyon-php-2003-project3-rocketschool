<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Faq;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqSearchFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('searchField')
            ->add('category', EntityType::class, [
                'placeholder' => 'Toutes les catégories',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
        ;
    }
}
