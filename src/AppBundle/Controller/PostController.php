<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Category;
use AppBundle\Form\Type\CategoryType;
use AppBundle\Form\Type\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostController extends Controller
{

    /**
     * @Route("post/new", name="post-new")
     */
    function addAction(Request $request)
    {
        $user = $this->getUser();
        if ($user != null) {
            if ($user->hasRole('ROLE_ADMIN')) {
                $product = new Product();
                $form = $this->createForm(ProductType::class, $product);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $task = $form->getData();
                    $file = $product->getImage();
                    $filename = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('image_path'), $filename);
                    $product->setImage($filename);
                    $em->persist($task);
                    $em->flush();
                    return $this->redirectToRoute('post-new');
                }

                $category = new Category();
                $formCategory = $this->createForm(CategoryType::class, $category);
                $formCategory->handleRequest($request);
                if ($formCategory->isSubmitted() && $formCategory->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $task = $formCategory->getData();
                    $em->persist($task);
                    $em->flush();
                    return $this->redirectToRoute('post-new');
                }
                return $this->render('/post/add.html.twig', [
                    'ProductForm' => $form->createView(),
                    'CategoryForm' => $formCategory->createView()
                ]);
            }
        }
        return $this->render('/post/add.html.twig');
    }


    /**
     * @Route("post/all", name="post-all")
     */

    public function allAction()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render('/post/all.html.twig', array('products' => $products));
    }


    /**
     * @Route("post/view/{id}" , name="post-view")
     */

    public function viewoneAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $task = $repository->find($id);

        return $this->render('post/view-one.html.twig', [
            'product' => $task,

        ]);
    }

    /**
     * @Route("post/category/{id}" , name="post-category")
     */

    public function viewcategoryAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $task = $repository->findByCategory($id);
        return $this->render('/post/all.html.twig', [
            'products' => $task,
        ]);
    }

    public function categoryAction()
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('/post/category.html.twig', array('category' => $category));
    }

    public function themeAction()
    {
        $category = $this->getDoctrine()->getRepository(Options::class)->findOneByName('theme');
        return $this->render('/post/theme.html.twig', array('theme' => $category));
    }


}





