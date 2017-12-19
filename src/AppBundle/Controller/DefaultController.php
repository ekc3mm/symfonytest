<?php

// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function mainAction()
    {
        return $this->render('default/main.html.twig');
    }

    /**
     * @Route("/newmail" , name="newmail")
     */

    public function newmailAction(Request $request, \Swift_Mailer $mailer)
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('to', TextType::class)
            ->add('title', TextType::class)
            ->add('text', TextareaType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = (new \Swift_Message($form->get('name')->getData()))
                ->setFrom('elerq9@gmail.com ')
                ->setTo($form->get('to')->getData())
                ->setBody($form->get('text')->getData());

            $mailer->send($message);
            return $this->redirectToRoute('newmail');
        }
        return $this->render('default/mailsend.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/random/{id}", name="random" , defaults={"id" : "10"})
     */

    public function randomAction($id)
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->request('GET', 'https://randomuser.me/api/?results=' . $id);
        $result = json_decode($request->getBody()->getContents(), true);
        return $this->render('/default/random.html.twig', array('users' => $result['results']));
    }

    /**
     * @Route("/git/{id}", name="git" , defaults={"id" : "symfony"})
     */

    public function gitAction(Request $request, $id)
    {
        $client = new \GuzzleHttp\Client();
        $user = $client->request('GET', 'https://api.github.com/users/' . $id);
        $user = json_decode($user->getBody()->getContents(), true);

        $repositories = $client->request('GET', 'https://api.github.com/users/' . $id . '/repos');
        $repos = json_decode($repositories->getBody()->getContents(), true);
        return $this->render('/default/git.html.twig', array('user' => $user, 'repos' => $repos));
    }

}


