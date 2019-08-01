<?php


namespace App\Controller\User\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Security;


class EditUserForm extends AbstractType
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userRoles=[
            'Admin' => 'ROLE_ADMIN',
            'USER' => 'ROLE_USER',
        ];
        $builder
            ->add('UserName', TextType::class,[
                'required'=>true,
                'label'=>'Пользователь'
            ])

            ->add('roles', ChoiceType::class,[
                'choices' => $userRoles,
                'expanded' => true,
                'multiple' => true,
                'label'=> 'Роль',
            ]);
    }
}