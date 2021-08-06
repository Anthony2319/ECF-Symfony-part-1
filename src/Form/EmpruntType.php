<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_emprunt')
            ->add('date_retour')
            ->add('livre', EntityType::class,[
                'livre'=> Livre::class,
                'choice_label' => function(Livre $livre) {
                        return "{$livre->getTitre()}";
                },

             ])
            ->add('emprunteur', EntityType::class,[
                'emprunter'=> Emprunter::class,
                'choice_label' => function(Emprunter $emprunter) {
                        return "{$emprunter->getNom()}, {$emprunter->getPrenom()}";
                },

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
