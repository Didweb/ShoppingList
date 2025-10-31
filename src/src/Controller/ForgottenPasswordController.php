<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\JsonResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class ForgottenPasswordController extends AbstractController
{
    private string $nameAppAndroid;
    private string $mailListas;

    public function __construct(string $nameAppAndroid, string $mailListas)
    {
        $this->nameAppAndroid = $nameAppAndroid;
        $this->mailListas = $mailListas;
    }

    #[Route('/forgot', name: 'forgot', methods: ['POST'])]
    public function forgot(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'])) {
            return JsonResponseFactory::error('Email is required.', 400);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if ($user) {
            $token = Uuid::v4()->toRfc4122();
            $user->setResetPasswordToken($token);
            $user->setResetPasswordExpiresAt(new \DateTimeImmutable('+1 hour'));
            $em->flush();

            
            $resetLink = sprintf('%s://reset-password?token=%s', $this->nameAppAndroid, $token);

            $email = (new Email())
                ->from($this->mailListas)
                ->to($data['email'])
                ->subject('Reset your password')
                ->html(sprintf('Click here to reset your password: <a href="%s">%s</a>', $resetLink, $resetLink));

            $mailer->send($email);
        }

        // Responder siempre igual, para no filtrar usuarios
        return JsonResponseFactory::success([
            'message' => 'If an account with that email exists, an email has been sent.'
        ]);
    }

    #[Route('/reset/{token}', name: 'reset', methods: ['POST'])]
    public function reset(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $userRepository->findOneBy(['resetPasswordToken' => $token]);

        if (!$user || $user->getResetPasswordExpiresAt() < new \DateTimeImmutable()) {
            return JsonResponseFactory::error('This reset link is invalid or expired.', 400);
        }

        $data = json_decode($request->getContent(), true);
        if (empty($data['password'])) {
            return JsonResponseFactory::error('Password is required.');
        }

        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
        $user->setResetPasswordToken(null);
        $user->setResetPasswordExpiresAt(null);
        $em->flush();

        return JsonResponseFactory::success([
            'message' => 'Your password has been reset.'
        ]);
        
    }
}