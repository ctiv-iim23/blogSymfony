<?php

namespace App\Form;

use App\Entity\Articles;
use App\Repository\CategoriesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $categories = $this->getCategories();

        $builder
            ->add('title')
            ->add('description')
            // ->add('createdAt')
            ->add('categorieId', ChoiceType::class, [
                'choices' => $categories
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }

    public function getCategories()
    {
        $repository = new CategoriesRepository();
        return $repository->findAll();
    }
}
