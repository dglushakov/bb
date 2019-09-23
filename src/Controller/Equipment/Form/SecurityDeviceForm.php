<?php


namespace App\Controller\Equipment\Form;


use App\Entity\Equipment;
use App\Entity\Facility;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class SecurityDeviceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('Equipment', EntityType::class, [
                'class' => Equipment::class,
                'label' => 'Устройство',
                'required'=>false,
                'choice_label' => 'model',

                'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')
                            ->andWhere('u.type NOT IN (:types)')
                            ->orderBy('u.type', 'ASC')
                            ->addOrderBy('u.make', 'ASC')
                            ->addOrderBy('u.model', 'ASC')
                            ->setParameter('types', ['Safe']);
                    },
            ])
            ->add('Serial', TextType::class, [
                'label' => 'Серийный номер',
                'required'=>true,
            ])
            ->add('Facility', EntityType::class, [
                'class' => Facility::class,
                'label' => 'Подразделение',
                'required'=>false,
                'choice_label' => 'address',
            ])
        ;


        $builder->add('Add new', SubmitType::class);
    }


}