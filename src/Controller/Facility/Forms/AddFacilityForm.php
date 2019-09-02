<?php


namespace App\Controller\Facility\Forms;


use App\Entity\Facility;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddFacilityForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $streetTypes = array_flip(Facility::getStreetTypes());

        $builder
            ->add('Name', TextType::class, [
                'required' => false,
                'label' => 'Имя',
            ])
            ->add('PostCode', TextType::class, [
                'required' => false,
                'label' => 'Индекс',
            ])
            ->add('StreetType', ChoiceType::class, [
                'choices' => $streetTypes,
                'label' => 'Тип улицы',
                'required' => true,
                'attr' => array('style' => 'width: 150px')
            ])
            ->add('Country', ChoiceType::class, [
                'choices' => Facility::COUNTRIES,
                'label' => 'Страна',
                'required' => true,
            ])
            ->add('Region', TextType::class, [
                'required' => true,
                'label' => 'Область',
            ])
            ->add('City', TextType::class, [
                'required' => true,
                'label' => 'Город',
            ])
            ->add('Street', TextType::class, [
                'required' => true,
                'label' => 'Улица',
            ])
            ->add('House', TextType::class, [
                'required' => false,
                'label' => 'Дом',
            ])
            ->add('Submit', SubmitType::class);
    }
}