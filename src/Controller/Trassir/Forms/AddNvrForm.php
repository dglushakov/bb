<?php


namespace App\Controller\Trassir\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddNvrForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Ip', TextType::class, [
                'required' => true,
                'label' => 'Ip',
            ]);
    }

}