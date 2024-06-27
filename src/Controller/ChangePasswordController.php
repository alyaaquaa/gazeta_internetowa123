<?php
/**
 * Controller for changing user passwords.
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ChangePasswordFormType;

class ChangePasswordController extends AbstractController
{
    /**
     * Constructor to initialize password hasher service.
     *
     * @param UserPasswordHasherInterface $passwordHasher Password hasher service
     */
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }


    /**
     * Change password action.
     *
     *
     * @param Request $request HTTP request
     * @return Response HTTP response
     */
    #[\Symfony\Component\Routing\Attribute\Route('/change-password', name: 'app_change_password')]
    #[Route(path: '/change-password', name: 'app_change_password')]
    public function changePassword(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();

            $encodedPassword = $this->passwordHasher->hashPassword(
                $user,
                $plainPassword
            );

            $user->setPassword($encodedPassword);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('change_password/index.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
