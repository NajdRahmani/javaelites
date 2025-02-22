<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Matiere;
use App\Form\CoursType;
use App\Repository\MatiereRepository;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Route('/cours')]
final class CoursController extends AbstractController
{
   
    #[Route(name: 'app_cours_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }
     // etudiant
    #[Route('/cours', name: 'app_cours_index2', methods: ['GET'])]
    public function index2(CoursRepository $coursRepository, MatiereRepository $matiereRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
            'matieres' => $matiereRepository->findAll(),
        ]);
    }
      // etudiant
      #[Route('/filter', name: 'app_cours_filter', methods: ['GET'])]
      public function filter(Request $request, CoursRepository $coursRepository, MatiereRepository $matiereRepository): Response
      {
          $matiereId = $request->query->get('matiere');
          $cours = $matiereId ? $coursRepository->findBy(['matiere' => $matiereId]) : $coursRepository->findAll();
  
          return $this->render('cours/index.html.twig', [
              'cours' => $cours,
              'matieres' => $matiereRepository->findAll(),
          ]);
      }
      // enseignant
    #[Route('/coursenseignant/{id}',name: 'coursEnseignant', methods: ['GET'])]
    public function index1(CoursRepository $coursRepository,$id): Response
    {
        return $this->render('cours/coursenseignant.html.twig', [
            'cours' => $coursRepository->findAll(),
            
        ]);
    }
     // enseignant
    #[Route('/enseignant/{id}', name: 'app_cours', methods: ['GET'])]
    public function show1(Cours $cour): Response
    {

        return $this->render('cours/showPourEnseignant.html.twig', [
            'cour' => $cour,
        ]);
    }
    // enseignant
    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour): Response
    {

        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
        ]);
    }

// enseignant
    #[Route('/new/{id}', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new($id,Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger,
    #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $brochuresDirectory
): Response
    {
        $matiere = $entityManager->getRepository(Matiere::class)->find($id);

        $cour = new Cours();
        $cour->setMatiere($matiere);
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $cour->setUrlImage($newFilename);
            }

            $entityManager->persist($cour);
            $entityManager->flush();

            return $this->redirectToRoute('app_chapitre_new',['id' => $cour->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }
    // enseignant
    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Cours $cour,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/brochures')] string $brochuresDirectory
    ): Response {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('brochure')->getData();
    
            // Gérer l'upload du fichier si un nouveau fichier est fourni
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
    
                // Déplacer le fichier dans le répertoire de stockage
                try {
                    $brochureFile->move($brochuresDirectory, $newFilename);
                } catch (FileException $e) {
                    // Gérer l'exception en cas d'erreur lors de l'upload
                    $this->addFlash('error', 'Une erreur s\'est produite lors de l\'upload du fichier.');
                    return $this->redirectToRoute('app_cours_edit', ['id' => $cour->getId()]);
                }
    
                // Supprimer l'ancien fichier s'il existe
                $oldFilename = $cour->getUrlImage();
                if ($oldFilename && file_exists($brochuresDirectory . '/' . $oldFilename)) {
                    unlink($brochuresDirectory . '/' . $oldFilename);
                }
    
                // Mettre à jour le nom du fichier dans l'entité
                $cour->setUrlImage($newFilename);
            }
    
            // Enregistrer les modifications en base de données
            $entityManager->flush();
    
            return $this->redirectToRoute('app_cours', ['id' => $cour->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }
// enseignant
    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('coursEnseignant', ['id'=>1], Response::HTTP_SEE_OTHER);
    }
}
