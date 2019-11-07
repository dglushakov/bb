<?php


namespace App\Controller\Equipment\Form;


use App\Entity\AlarmSystem;
use App\Entity\Facility;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddAlarmSystemForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('securityProvider', ChoiceType::class, [
                'label' => 'Подрядчик',
                'required' => false,
                'choices' => AlarmSystem::getSecurityProvidersList(),
            ])
            ->add('pkp', IntegerType::class, [
                'label' => 'ПКП',
                'required' => false,
                'empty_data'=> 0,
            ])
            ->add('keyboard', IntegerType::class, [
                'label' => 'Клавиатура',
                'required' => false,
                'empty_data'=> 0,
            ])
            ->add('motion_sensor', IntegerType::class, [
                'label' => 'Датчик движения',
                'required' => false,
                'empty_data'=> 0,
            ])
            ->add('fire_sensor', IntegerType::class, [
                'label' => 'Пожарный датчик',
                'required' => false,
                'empty_data'=> 0,
            ])
            ->add('door_sensor', IntegerType::class, [
                'label' => 'Датчик открытия двери',
                'required' => false,
                'empty_data'=> 0,
            ])
            ->add('stationary_button', IntegerType::class, [
                'label' => 'Кнопка тревожная стационарная',
                'required' => false,
                'empty_data'=> 0,
            ])
            ->add('wearable_button', IntegerType::class, [
                'label' => 'Кнопка тревожная носимая',
                'required' => false,
                'empty_data'=> 0,
            ])
            ->add('Facility', EntityType::class, [
                'class' => Facility::class,
                'choice_label' => 'address',
                'required'=>true,
                'label'=>'Подразделение',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->addOrderBy('u.country', 'ASC')
                        ->addOrderBy('u.city', 'ASC')
                        ->addOrderBy('u.street', 'ASC');
                },
            ])
            ->add('OK', SubmitType::class)
        ;
    }

}