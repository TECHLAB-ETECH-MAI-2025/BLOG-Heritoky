<?php
namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageTypeForm;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;


#[Route('/chat')]
class ChatController extends AbstractController
{
    #[ROUTE ('/liste', name: 'liste_utilisateur_chat')]
    public function listeUtilisateurChat(EntityManagerInterface $entityManagerInterface): Response
    {
        $userChatListe = $entityManagerInterface->getRepository(User::class)->findAll();

        return $this->render('/chat/listeUtilisateur.html.twig',[
            'liste'=>$userChatListe
        ]);
    }

    #[Route('/{receiverId}', name: 'chat_index')]
    public function index(
        string $receiverId,
        MessageRepository $messageRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        HubInterface $hub
    ): Response {
        /** @var User $currentUser */ 
        $receiverId = (int) $receiverId;
        $currentUser = $this->getUser();
        if (!$currentUser instanceof User) {
            throw $this->createAccessDeniedException('Vous devez être connecté.');
        }

        $receiver = $entityManager->getRepository(User::class)->find($receiverId);
        if (!$receiver) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        $messages = $messageRepository->findConversation($currentUser->getId(), $receiverId);

        $message = new Message();
        $form = $this->createForm(MessageTypeForm::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($currentUser);
            $message->setReceiver($receiver);
            $message->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($message);
            $entityManager->flush();

              $update = new Update([
                sprintf("/chat/%d", $receiverId),'https://example.com/books/1'], 
                json_encode([
                    'message' => $message->getContent(),
                    'sender' => $currentUser->getId(),
                    'receiver' => $receiverId,
                    'statu' => true

        ]));
    $hub->publish($update);


    return $this->redirectToRoute('chat_index', ['receiverId' => $receiverId]);
}

        // Render the chat view if the form is not submitted or not valid
        return $this->render('/chat/index.html.twig', [
            'messages' => $messages,
            'form' => $form->createView(),
            'receiver' => $receiver,
        ]);
    }

    
    
}