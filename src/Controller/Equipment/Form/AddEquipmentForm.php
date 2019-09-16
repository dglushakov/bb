<?php


namespace App\Controller\Equipment\Form;


use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddEquipmentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('Type', ChoiceType::class,
            [
                'choices' => Equipment::getEquipmentTypes(),
                'label' => 'Тип',
                'required' => true,
            ])
            ->add('Make', ChoiceType::class,
                [
                    'choices' => Equipment::getEquipmentMakes(),
                    'label' => 'Марка',
                    'required' => true,
                ])
            ->add('Model', TextType::class, [
                'label' => 'Модель'
            ]);
    }

}