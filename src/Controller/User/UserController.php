<?php


namespace App\Controller\User;


use App\Controller\User\Forms\AddUserForm;
use App\Controller\User\Forms\EditUserForm;
use App\Controller\User\Forms\EditUserPasswordForm;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{

    /**
     * @Route("/userlist", name="userlist")
     */
    public function userlist(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USERS_VIEW');
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $allUsers = $userRepo->findAll();

        $addUserForm = $this->createForm(AddUserForm::class, new User());
        $addUserForm->handleRequest($request);
        if ($addUserForm->isSubmitted() && $addUserForm->isValid()) {
            $newUser = $addUserForm->getData();
            $plainPassword = $newUser->getPassword();
            $encoded = $encoder->encodePassword($newUser, $plainPassword);
            $newUser->setPassword($encoded);
            $em->persist($newUser);
            $em->flush();
            return $this->redirectToRoute('userlist');
        }



        return $this->render('user/userlist.html.twig', [
            'users'=>$allUsers,
            'addUserForm' => $addUserForm->createView(),
            ]);
    }

    /**
     * @Route ("user/edit/{id}", name="editUser")
     */
    public function editUser(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USERS_EDIT');
        $usersRepo = $this->getDoctrine()->getRepository(User::class);
        $userToEdit = $usersRepo->find($id);

        $EditUserForm = $this->createForm(EditUserForm::class, $userToEdit);

        $EditUserForm->handleRequest($request);
        if ($EditUserForm->isSubmitted() && $EditUserForm->isValid()) {
            $userToEdit = $EditUserForm->getData();
            $em->persist($userToEdit);
            $em->flush();
            return $this->redirectToRoute('userlist');
        }

        $EditUserPasswordForm = $this->createForm(EditUserPasswordForm::class, $userToEdit);
        $EditUserPasswordForm->handleRequest($request);
        if ($EditUserPasswordForm->isSubmitted() && $EditUserPasswordForm->isValid()) {
            $userToEdit = $EditUserForm->getData();
            $plainPassword = $EditUserForm->getData()->getPassword();
            $encoded = $encoder->encodePassword($userToEdit, $plainPassword);
            $userToEdit->setPassword($encoded);
            $em->persist($userToEdit);
            $em->flush();
            return $this->redirectToRoute('editUser', [
                'id'=>$userToEdit->getId(),
            ]);
        }



        return $this->render('User/editUser.html.twig',[
            'editUserForm'=>$EditUserForm->createView(),
            'editUserPasswordForm'=>$EditUserPasswordForm->createView(),
        ]);
    }


    /**
     * @Route ("user/delete/{id}", name="deleteUser")
     */
    public function deleteUser(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USERS_DELETE');
        $usersRepo = $this->getDoctrine()->getRepository(User::class);
        $userToDelete = $usersRepo->find($id);
        if ($userToDelete) {
            $em->remove($userToDelete);
            $em->flush();
        }
        return $this->redirectToRoute('userlist');
    }

}