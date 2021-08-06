<?php

namespace App\Form;


use App\Entity\User;
use App\Entity\Emprunteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EmprunteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserType::class, [
                'label_attr' => [
                    'class' => 'd-none',
                ]
            ])
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('actif')
            ->add('date_creation')
            ->add('date_modification')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunteur::class,
        ]);
    }
}
