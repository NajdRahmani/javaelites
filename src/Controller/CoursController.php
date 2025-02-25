<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Matiere;
use App\Entity\User;
use App\Form\CoursType;
use App\Repository\MatiereRepository;
use App\Repository\CoursRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Security;

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
    #[Route('/cours3', name: 'app_cours_index3', methods: ['GET'])]
    public function index3(
        Request $request,
        CoursRepository $coursRepository,
        MatiereRepository $matiereRepository,
        UserRepository $userRepository
    ): Response {
        $session = $request->getSession();
        $userId = $session->get('id'); // Récupère l'ID de l'utilisateur depuis la session
    
        if (!$userId) {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }
    
        $user = $userRepository->find($userId);
    
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }
    
        // Récupère les cours où l'utilisateur est inscrit
        $cours = $coursRepository->createQueryBuilder('c')
            ->innerJoin('c.etudiants', 'e') // Joindre la relation "etudiants"
            ->where('e.id = :userId') // Filtrer par l'ID de l'utilisateur
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    
        return $this->render('cours/index3.html.twig', [
            'cours' => $cours,
            'matieres' => $matiereRepository->findAll(),
        ]);
    }
    #[Route('/cours4', name: 'app_cours_index4', methods: ['GET'])]
    public function index4(
        Request $request,
        CoursRepository $coursRepository,
        MatiereRepository $matiereRepository,
        UserRepository $userRepository
    ): Response {
        $session = $request->getSession();
        $userId = $session->get('id'); // Récupère l'ID de l'utilisateur depuis la session
    
        if (!$userId) {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }
    
        $user = $userRepository->find($userId);
    
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }
    
        // Récupère les cours où l'utilisateur est inscrit
        $cours = $coursRepository->createQueryBuilder('c')
            ->innerJoin('c.etudiantsfavoris', 'e') // Joindre la relation "etudiants"
            ->where('e.id = :userId') // Filtrer par l'ID de l'utilisateur
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    
        return $this->render('cours/index1.html.twig', [
            'cours' => $cours,
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
      #[Route('/filter1', name: 'app_cours_filter1', methods: ['GET'])]
public function filter1(
    Request $request,
    CoursRepository $coursRepository,
    MatiereRepository $matiereRepository,
    UserRepository $userRepository
): Response {
    $session = $request->getSession();
    $userId = $session->get('id'); // Récupère l'ID de l'utilisateur connecté

    if (!$userId) {
        throw $this->createAccessDeniedException('Utilisateur non connecté.');
    }

    $user = $userRepository->find($userId);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur introuvable.');
    }

    // Filtre pour la matière
    $matiereId = $request->query->get('matiere');

    // Récupération des cours auxquels l'utilisateur est inscrit, avec un filtre par matière (si fourni)
    $queryBuilder = $coursRepository->createQueryBuilder('c')
        ->innerJoin('c.etudiants', 'e') // Joindre la relation "etudiants"
        ->where('e.id = :userId') // Filtrer par l'utilisateur connecté
        ->setParameter('userId', $userId);

    if ($matiereId) {
        $queryBuilder->andWhere('c.matiere = :matiereId') // Ajouter le filtre par matière
            ->setParameter('matiereId', $matiereId);
    }

    $cours = $queryBuilder->getQuery()->getResult();

    return $this->render('cours/index.html.twig', [
        'cours' => $cours,
        'matieres' => $matiereRepository->findAll(),
    ]);
}
#[Route('/filter2', name: 'app_cours_filter2', methods: ['GET'])]
public function filter2(
    Request $request,
    CoursRepository $coursRepository,
    MatiereRepository $matiereRepository,
    UserRepository $userRepository
): Response {
    $session = $request->getSession();
    $userId = $session->get('id'); // Récupère l'ID de l'utilisateur connecté

    if (!$userId) {
        throw $this->createAccessDeniedException('Utilisateur non connecté.');
    }

    $user = $userRepository->find($userId);

    if (!$user) {
        throw $this->createNotFoundException('Utilisateur introuvable.');
    }

    // Filtre pour la matière
    $matiereId = $request->query->get('matiere');

    // Récupération des cours auxquels l'utilisateur est inscrit, avec un filtre par matière (si fourni)
    $queryBuilder = $coursRepository->createQueryBuilder('c')
        ->innerJoin('c.etudiantsfavoris', 'e') // Joindre la relation "etudiants"
        ->where('e.id = :userId') // Filtrer par l'utilisateur connecté
        ->setParameter('userId', $userId);

    if ($matiereId) {
        $queryBuilder->andWhere('c.matiere = :matiereId') // Ajouter le filtre par matière
            ->setParameter('matiereId', $matiereId);
    }

    $cours = $queryBuilder->getQuery()->getResult();

    return $this->render('cours/index1.html.twig', [
        'cours' => $cours,
        'matieres' => $matiereRepository->findAll(),
    ]);
}

      // enseignant
    #[Route('/coursenseignant',name: 'coursEnseignant', methods: ['GET'])]
    public function index1(CoursRepository $coursRepository,Request $request, EntityManagerInterface $entityManager): Response
    { $session = $request->getSession();
        $userId = $session->get('id');
        $enseignant = $entityManager->getRepository(User::class)->find($userId);

        return $this->render('cours/coursenseignant.html.twig', [
            'cours' => $coursRepository->findByEnseignant($enseignant),
            
        ]);
    }
    
    #[Route('/votre-route-confirmation/{id}', name: 'route2')]
public function confirmationAction(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    // Récupération de l'utilisateur
    $userId = $request->getSession()->get('id');
    $user = $entityManager->getRepository(User::class)->find($userId);
    
    if (!$user) {
        $this->addFlash('error', 'Utilisateur non trouvé');
        return $this->redirectToRoute('login_route');
    }

    // Récupération du cours
    $cour = $entityManager->getRepository(Cours::class)->find($id);
    
    if (!$cour) {
        $this->addFlash('error', 'Cours non trouvé');
        return $this->redirectToRoute('cours_list_route');
    }

    try {
        // Ajout de l'étudiant
        $cour->addEtudiant($user);
        
        // Pas besoin de persist() pour les entités existantes
        $entityManager->flush();

        $this->addFlash('success', 'Inscription réussie !');
        
    } catch (\Exception $e) {
        $this->addFlash('error', 'Erreur lors de l\'inscription : '.$e->getMessage());
    }

    return $this->redirectToRoute('app_cours_show', [
        'id' => $cour->getId()
    ], Response::HTTP_SEE_OTHER);
}
     // enseignant
     #[Route('/enseignant/{id}', name: 'app_cours', methods: ['GET'])]     
     public function show1(int $id, Request $request, EntityManagerInterface $entityManager): Response
     {
         $cour = $entityManager->getRepository(Cours::class)->find($id);
     
         if (!$cour) {
             throw $this->createNotFoundException('Le cours demandé n\'existe pas.');
         }
     
         $session = $request->getSession();
         $userId = $session->get('id');
     
         if ($cour->getEnseignant()->getId() === $userId) {
             return $this->render('cours/showPourEnseignant.html.twig', [
                 'cour' => $cour,
             ]);
         } else {
             return $this->redirectToRoute('coursEnseignant');
         }
     }
     
     
    // etudiant
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
    {   $session = $request->getSession();
        $userId = $session->get('id');
       
        $enseignant = $entityManager->getRepository(User::class)->find($userId);
        $cour = new Cours();
        $cour->setEnseignant($enseignant);
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
        $userId = $request->getSession()->get('id');
        $user = $entityManager->getRepository(User::class)->find($userId);
        $enseignant=$cour->getEnseignant();
        if($user->getId()==$enseignant->getId() || $user->getRoles()=="Admin" ){
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
        }}
        else{
            return $this->redirectToRoute('app_login');
        }
    
        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

#[Route('/{id}/favorite', name: 'app_cours_toggle_favorite', methods: ['POST'])]
public function toggleFavorite(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
{
    $userId = $request->getSession()->get('id');
    $user = $entityManager->getRepository(User::class)->find($userId);

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    if ($cour->isUserFavorite($userId)) {
        $cour->removeEtudiantfavoris($user);
    } else {
        $cour->addEtudiantfavoris($user);
    }

    $entityManager->flush();

    return $this->redirectToRoute('app_cours_show', ['id' => $cour->getId()]);
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
