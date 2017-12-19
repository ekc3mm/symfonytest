<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Form\Type\TaskType;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaskController extends Controller
{
    /**
     * @Route("task/new", name="task-new")
     */

    public function newAction(Request $request)
    {
        $form = $this->createForm(TaskType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $task = $form->getData();
            $task->setUser($this->getUser()->getId());
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('task-my');
        }

        return $this->render(':task:new.html.twig', [
            'TaskForm' => $form->createView()
        ]);

    }

    /**
     * @Route("/task/my/{id}", name="task-user")
     */

    public function allAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $repository->findBy(array('user' => $id));

        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(array('id' => $id));

        return $this->render('task/user.html.twig', [
            'tasks' => $tasks,
            'header' => $user->getUsername() . ' tasks',
            'user' => $user
        ]);

    }

    /**
     * @Route("/task/mytasks", name="task-my")
     */

    public function myAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $repository->findBy(array('user' => $this->getUser()->getId()));

        return $this->render('task/user.html.twig', [
            'tasks' => $tasks,
            'header' => $this->getUser() . ' Tasks',
        ]);

    }

    /**
     * @Route("task/view/{id}" , name="task-view")
     */

    public function viewoneAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->find($id);
        return $this->render('task/view-one.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("task/edit/{id}" , name="task-edit")
     */

    public function editAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->find($id);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task = $form->getData();
            $task->setUser($this->getUser()->getId());
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('task-my');
        }

        return $this->render(':task:new.html.twig', [
            'TaskForm' => $form->createView()
        ]);

    }

    /**
     * @Route("task/delete/{id}" , name="task-delete")
     */
    public function deleteAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('task-my');
    }


    /**
     * @Route("task/copy/{id}" , name="task-copy")
     */

    public function copyAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        $task = $repository->find($id);
        $form = $this->createForm(TaskType::class, clone $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $task = $form->getData();
            $task->setUser($this->getUser()->getId());
            $em->persist($task);
            $em->flush();
            return $this->redirectToRoute('task-my');
        }

        return $this->render(':task:new.html.twig', [
            'TaskForm' => $form->createView()
        ]);
    }
}