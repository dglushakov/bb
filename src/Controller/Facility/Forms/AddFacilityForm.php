<?php


namespace App\Controller\Facility\Forms;


use App\Entity\Facility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddFacilityForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('Name', TextType::class, [
                'required' => false,
                'label' => 'Имя',
            ])
            ->add('PostCode', TextType::class, [
                'required' => false,
                'label' => 'Индекс',
            ])

            ->add('Country', ChoiceType::class, [
                'choices' => Facility::COUNTRIES,
                'label' => 'Страна',
                'required' => false,
            ])
            ->add('Region', TextType::class, [
                'required' => true,
                'label' => 'Область',

                'attr' =>
                    [
                    'readonly'=>true,
                ]
            ])
            ->add(
                'City', ChoiceType::class, [
                    'required' => false,
                    'label' => 'Город',
                    'choices' => []
                ])
            ->add('StreetType', ChoiceType::class, [
                'choices' => array_flip(Facility::getStreetTypes()),
                'label' => 'Тип улицы',
                'required' => true,
            ])
            ->add('Street', TextType::class, [
                'required' => true,
                'label' => 'Название улицы',
            ])
            ->add('House', TextType::class, [
                'required' => true,
                'label' => 'Номер дома',
            ])
            ->add('BuildingType', ChoiceType::class, [
                'required' => false,
                'label' => 'Тип строения',
                'choices' => array_flip(Facility::getBuildingTypes())
            ])
            ->add('Building', TextType::class, [
                'required' => false,
                'label' => 'Номер строения',
            ])
            ->add('Room', TextType::class, [
                'required' => false,
                'label' => 'Номер помещения',
            ])
            ->add('Submit', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Facility::class,
        ]);
    }

}