<?php


namespace App\Controller\Trassir\Forms;


use App\Entity\Facility;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditTrassirNvrForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Ip', TextType::class,[
                'required'=>true,
                'label'=>'Ip Address'
            ])->add('Facility', EntityType::class,[
                'class'=>Facility::class,
                'choice_label'=>'Address',
                'required'=>false,
                ])
        ;
    }

}