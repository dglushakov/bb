<?php


namespace App\Controller\User\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;

class AddUserForm extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userRoles = [
            'Admin' => 'ROLE_ADMIN',
            'USER' => 'ROLE_USER',
        ];

        $builder
            ->add('UserName', TextType::class, [
                'required' => true,
                'label' => 'Пользователь',

                'attr'=>[
                    'placeholder' => 'form.order.id.placeholder',
                    'title' => 'form.order.id.title',
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'Пароль',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $userRoles,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Роль',
            ]);
    }
}