<?php


namespace App\Controller\Facility\Forms;


use App\Entity\Facility;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditFacilityForm extends AbstractType
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
            ->add('lmcode', TextType::class, [
                'required' => false,
                'label' => 'Lme код',
            ])
            ->add('StreetType', ChoiceType::class, [
                'choices' => array_flip(Facility::getStreetTypes()),
                'label' => 'Тип улицы',
                'required' => true,
                'attr' => array('style' => 'width: 150px')
            ])
            ->add('Street', TextType::class, [
                'required' => true,
                'label' => 'Улица',
                'attr' => array('class' => 'text-capitalize'),
            ])
            ->add('House', TextType::class, [
                'required' => true,
                'label' => 'Дом',
            ])
            ->add('BuildingType', ChoiceType::class, [
                'required' => false,
                'label' => 'Тип строения',
                'choices' => array_flip(Facility::getBuildingTypes())
            ])
            ->add('Building', TextType::class, [
                'required' => false,
                'label' => 'Строение',
            ])
            ->add('Room', TextType::class, [
                'required' => false,
                'label' => 'Помещение',
            ])
            ->add('Lat', TextType::class, [
                'required' => false,
                'label' => 'Latitude',
            ])
            ->add('Lon', TextType::class, [
                'required' => false,
                'label' => 'Longitude',
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