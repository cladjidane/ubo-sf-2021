<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Page;
use App\Entity\Portfolio;
use App\Form\PageType;

use Symfony\Component\Validator\Constraints as Assert;


class PagesController extends AbstractController {
 
    /**
     * @Route("/", name="home")
     */
    public function index(): Response {
        return $this->render('pages/index.html.twig', [
            'title' => 'HOME',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response {
        return $this->render('pages/contact.html.twig', [
            'title' => 'CONTACT',
        ]);
    }

    /**
     * @Route("/portfolio/{slug}", name="porfolio")
     */
    public function portfolio(string $slug): Response {
        $repository = $this->getDoctrine()->getRepository(Portfolio::class);
        //$Portfolio = $repository->find($id);
        $Portfolios = $repository->findBy(array("user" => $slug));

        return $this->render('pages/portfolio.html.twig', [
            'portfolios' => $Portfolios
        ]);
    }   

    /**
     * @Route("/page/{id}", name="page")
     */
    public function page(int $id): Response {
        $repository = $this->getDoctrine()->getRepository(Page::class);
        $Page = $repository->find($id);

        return $this->render('pages/page.html.twig', [
            'title' => $Page->getTitle(),
            'content' => $Page->getContent(),
        ]);
    }

    /**
     * @Route("/pages", name="pages")
     */
    public function pages(Request $request): Response {
        $repository = $this->getDoctrine()->getRepository(Page::class);
        $Pages = $repository->findAll();

        // Création Form
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();

            return $this->redirectToRoute("pages");
        }

        return $this->render('pages/pages.html.twig', [
            'title' => "Liste des pages",
            "form_page" => $form->createView(),
            'pages' => $Pages,
        ]);
    }

    /**
     * @Route("/createpage", name="createpage")
     */
    public function createpage(Request $request): Response {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($page);
            $entityManager->flush();
        }

        return $this->render("pages/createpage.html.twig", [
            "form_title" => "Ajouter une page",
            "form_page" => $form->createView(),
        ]);
    }

    /**
     * @Route("/createpage/{id}", name="updatepage")
     */
    public function updatepage(Request $request, int $id): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $page = $entityManager->getRepository(Page::class)->find($id);
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
        }

        return $this->render("pages/updatepage.html.twig", [
            "form_title" => "Modifier une page",
            "form_page" => $form->createView(),
        ]);
    }

    /**
     * @Route("/delpage/{id}", name="delpage")
     */
    public function delpage(int $id): Response {
        $entityManager = $this->getDoctrine()->getManager();
        $page = $entityManager->getRepository(Page::class)->find($id);
        
        $entityManager->remove($page);
        $entityManager->flush();

        return $this->redirectToRoute("pages");
    }


}
