<?php


namespace App\Controller\Equipment\Form;


use App\Entity\Equipment;
use App\Entity\Facility;
use App\Entity\Safe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddSafeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Equipment', EntityType::class, [
                'class' => Equipment::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.type=:type')
                        ->orderBy('u.make', 'ASC')
                        ->addOrderBy('u.model', 'ASC')
                        ->setParameter('type', 'safe');
                },
                'choice_label' => 'model',
            ])
            ->add('Serial', TextType::class, [
                'label' => 'Serial',
                'required' => true,
            ])
            ->add('Facility', EntityType::class, [
                'class' => Facility::class,
                'choice_label' => 'address',
                'required'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->addOrderBy('u.country', 'ASC')
                        ->addOrderBy('u.city', 'ASC')
                        ->addOrderBy('u.street', 'ASC');
                },
            ])
            ->add('Status', ChoiceType::class, [
                'choices' => Safe::getStatuses(),
                'label' => 'Status',
            ])
            ->add('Comment', TextType::class, [
                'label' => 'Comment',
                'required' => false,
            ]);
    }

}