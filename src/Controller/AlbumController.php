<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Album;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AlbumRepository;
use App\Form\AlbumType;

class AlbumController extends AbstractController
{
    /**
     * @Route("/album", name="app_album")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $albums = $entityManager->getRepository(Album::class)->findAll();
        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }
        /**
     * Show a album
     * 
     * @Route("/album/{id}", name="album_show", requirements={"id"="\d+"})
     *    note that the id must be an integer, above
     *    
     * @param Integer $id
     */
    public function show(ManagerRegistry $doctrine, $id)
    {
        $albumRepo = $doctrine->getRepository(Album::class);
        $album = $albumRepo->find($id);

        if (!$album) {
            throw $this->createNotFoundException('The album does not exist');
        }

        return $this->render("album/show.html.twig", [
            "album" => $album,
        ]);
    }
    /**
     * @Route("/album/{id}/edit", name="album_edit", methods={"GET", "POST"})

     */
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $albumRepository->add($album, true);

            $this->addFlash('success','OK');
            return $this->redirectToRoute('app_album', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
         * @Route("/album/create", name="album_create", methods={"GET", "POST"})

        */
        public function create(Request $request, AlbumRepository $albumRepository): Response
        {
            $album = new Album();
            $form = $this->createForm(AlbumType::class, $album);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $albumRepository->add($album, true);

                $this->addFlash('success','OK');
                return $this->redirectToRoute('app_album', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('album/create.html.twig', [
                'form' => $form,
            ]);
        }

    /**
     * @Route("/album/{id}/delete", name="album_delete", methods={"POST"})
     * 
     */
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $album->getId(), $request->request->get('_token'))) {
            $this->addFlash('success', 'OK');
            $albumRepository->remove($album, true);

        }

        return $this->redirectToRoute('app_album', [], Response::HTTP_SEE_OTHER);
    }
}
