<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Page;
use App\Entity\Portfolio;
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
     * @Route("/createpage", name="createpage")
     */
    public function createpage(): Response {
        $entityManager = $this->getDoctrine()->getManager();

        $Page = new Page();
        $Page->setTitle('Nouveau titre'.rand());
        $Page->setContent('lorem ipsum ...');
        $Page->setCreatedAt(new \DateTime());

        $entityManager->persist($Page);
        $entityManager->flush();

        // Liste pages
        $repository = $this->getDoctrine()->getRepository(Page::class);
        $pages = $repository->findAll();

        return $this->render('pages/createpage.html.twig', [
            'info' => 'Nouvelle page insérée '.$Page->getId(),
            'pages' => $pages
        ]);
    }
}
