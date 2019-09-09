<?php


namespace App\Controller\User\Forms;


use App\Entity\User;
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
        $userRoles= User::getUserRoles();

        $builder
            ->add('UserName', TextType::class, [
                'required' => true,
                'label' => 'Пользователь',

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