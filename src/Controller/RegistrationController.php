<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/registration', name: 'registration')]
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
            ])
            ->add('register', type: SubmitType::class)
            ->getForm();;

        $form->handleRequest($request);
        if (($form->isSubmitted())) {
            $data = $form->getData();
            $user = new User();
            $user->setUsername($data['username']);
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $data['password'])
            );
            dump($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('app_login'));
        }
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
//public function registerAction(Request $request)
//{
//    // Create a new blank user and process the form
//    $user = new User();
//    $form = $this->createForm(UserType::class, $user);
//    $form->handleRequest($request);
//
//    if ($form->isSubmitted() && $form->isValid()) {
//        // Encode the new users password
//        $encoder = $this->get('security.password_encoder');
//        $password = $encoder->encodePassword($user, $user->getPlainPassword());
//        $user->setPassword($password);
//
//        // Set their role
//        $user->setRole('ROLE_USER');
//
//        // Save
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($user);
//        $em->flush();
//
//        return $this->redirectToRoute('login');
//    }
//
//    return $this->render('auth/register.html.twig', [
//        'form' => $form->createView(),
//    ]);
//}
//}
//
//
//
//
