<?php

namespace App\Controller;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserType2;
use App\Form\User1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request,UserRepository $repo, SessionInterface $session,AuthenticationUtils $authenticationUtils): Response
    {
        if ($request->isMethod('POST')) {
           
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $user = $repo->findOneBy(['email' => $email]);
          
            
            if (!$user)  {
                $this->addFlash('error', "Email n'existe pas ");
                return $this->render('user/login.html.twig');
             
            }
            if ($user->getDeleted()  == 1){
               
                return $this->redirectToRoute('logout'); 
            }
            else {
            
                $hashpass=$user->getPassword();  
            
            if ($isValid = password_verify($password, $hashpass)) {
                $session->set('email', $user->getEmail());
                $session->set('id', $user->getId());
                $session->set('test', $user->getEmail());
                $session->set('user_nom', $user->getNom());
                $session->set('user_prenom', $user->getPrenom());
                $session->set('localisation', $user->getLocalisation());
                $session->set('image', $user->getImage());
                $session->set('roles', $user->getRoles());
    
                if ( "Etudiant"== $user->getRoles()) {
                
                return $this->redirectToRoute('front'); 
             }
             if ( "Enseignant"== $user->getRoles()) {
                
                return $this->redirectToRoute('backenseignant'); 
             }
             if ( "Admin"== $user->getRoles()) {
                
                return $this->redirectToRoute('back'); 
             }
             else{
                return $this->redirectToRoute('app_login');
            }
            }
            $this->addFlash('error', "Mot de pass Incorrect ");
        return $this->render('user/login.html.twig');
        }
        }
        return $this->render('user/login.html.twig');
    }


    #[Route('/user/logout', name: 'logout')]
public function logout(SessionInterface $session)
{
    $session->invalidate(); 
   
    return $this->redirectToRoute('app_login'); 
}
    #[Route('/user/front', name: 'front')]
    public function front(): Response
    {
         return $this->render('user/front.html.twig');  
    }
    

    #[Route('/user/backenseignant', name: 'backenseignant')]
    public function backenseignant(): Response
    {
         return $this->render('user/backenseignant.html.twig');  
    }
    #[Route('/user/back', name: 'back')]
    public function back(): Response
    {
         return $this->render('user/back.html.twig');  
    }
    #[Route('/user/choice', name: 'choice')]
    public function choice(): Response
    {
         return $this->render('user/choix.html.twig');  
    }


    #[Route('/user/inscriptionetudiant', name: 'inscriptionetudiant')]
  
    public function inscriptionetudiant(Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher,UserRepository $repo)
{
  
    $user= new User();
    $user->setRoles("Etudiant");
    $user->setDeleted(0);
    $user->setBanned(0);
    $user->setGradeLevel(0);
    $user->setSpecialite(0); 
$form =$this->createForm(UserType::class, $user);
$form->handleRequest($request);

 if($form->isSubmitted()&& $form->isValid())
 { dump($form->getErrors(true)); 
    $test = $repo->findOneBy(['email' => $user->getEmail()]);
    if ($user->getPassword() !== $user->getConfirmPassword() ) { 
        return $this->render('user/inscription.html.twig',[
            'form' => $form->createView(),
            ]);
     
    }
    if($test != null ){
         return $this->render('user/inscription.html.twig',[
            'form' => $form->createView(),
            ]);
    }
    $imageFile = $form->get('image')->getData();
    if ($imageFile = $form->get('image')->getData() != null){
        $imageFile = $form->get('image')->getData();
        $filename= uniqid() . '-' . $imageFile->getClientOriginalName();
        $imageFile->move(
            $this->getParameter('upload_directory'),
            $filename
        );

    
    $user->setImage($filename);
    }
    else {
        $user->setImage('inconnu.jpg');  
    }  
$pass = $user->getPassword();
$hashedPassword = password_hash($pass, PASSWORD_BCRYPT);
$user->setPassword($hashedPassword);
$user->setConfirmPassword($hashedPassword);
 $em->persist($user);
 $em->flush();

 return $this->redirectToRoute('app_login');

}
 else 
 {

 return $this->render('user/inscription.html.twig',[
'form' => $form->createView(),
]);
 }

}



#[Route('/user/inscriptionenseignant', name: 'inscriptionenseignant')]
  
public function inscriptionenseignant(Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher,UserRepository $repo)
{

$user= new User();
$user->setRoles("Enseignant");
$user->setDeleted(0);
$user->setBanned(0);
$user->setGradeLevel(null);

$form =$this->createForm(User1Type::class, $user);
$form->handleRequest($request);

if($form->isSubmitted()&& $form->isValid())
{
$test = $repo->findOneBy(['email' => $user->getEmail()]);
if ($user->getPassword() !== $user->getConfirmPassword() ) { 
    return $this->render('user/inscriptionenseignant.html.twig',[
        'form' => $form->createView(),
        ]);
 
}
if($test != null ){
     return $this->render('user/inscriptionenseignant.html.twig',[
        'form' => $form->createView(),
        ]);
}
$imageFile = $form->get('image')->getData();
        if ($imageFile = $form->get('image')->getData() != null){
            $imageFile = $form->get('image')->getData();
            $filename= uniqid() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(
                $this->getParameter('upload_directory'),
                $filename
            );

        
        $user->setImage($filename);
        }
else {
    $user->setImage('inconnu.jpg');  
}  
$pass = $user->getPassword();
$hashedPassword = password_hash($pass, PASSWORD_BCRYPT);
$user->setPassword($hashedPassword);
$user->setConfirmPassword($hashedPassword);
$em->persist($user);
$em->flush();

return $this->redirectToRoute('app_login');

}
else 
{

return $this->render('user/inscriptionenseignant.html.twig',[
'form' => $form->createView(),
]);
}

}


#[Route('/user/back/{id}/remove/admin',name:'removadmin')]
public function deleteeadmin(UserRepository $repo,EntityManagerInterface $em,int $id){
 $user =$repo->find($id);
 if($user){
    $em->remove($user);
    $em->flush();
    return $this->redirectToRoute('corbeilleadmin');
 }
}

#[Route('/user/back/{id}/remove/etudiant',name:'removeuser')]
public function deleteetudiant(UserRepository $repo,EntityManagerInterface $em,int $id){
 $user =$repo->find($id);
 if($user){
    $em->remove($user);
    $em->flush();
    return $this->redirectToRoute('corbeilleetudiant');
 }
}
#[Route('/user/back/{id}/remove/enseignant',name:'removenseignant')]
public function deleteenseignant(UserRepository $repo,EntityManagerInterface $em,int $id){
 $user =$repo->find($id);
 if($user){
    $em->remove($user);
    $em->flush();
    return $this->redirectToRoute('corbeillenseignant');
 }
}
#[Route('/user/back/{id}/corbeille/etudiant', name: 'envoyerCorbeilleetudiant')]
public function envoyercorbeiiilleetudiant(UserRepository $repo, Request $request, EntityManagerInterface $em,int $id)
{
    $user =$repo->find($id);
    $user->setDeleted(1); 
    $em->flush();
    return $this->redirectToRoute('showalletudiant');
}
#[Route('/user/back/{id}/corbeille/admin', name: 'envoyerCorbeilleadmin')]
public function envoyercorbeiiilleadmin(UserRepository $repo, Request $request, EntityManagerInterface $em,int $id,SessionInterface $session)
{
    $user =$repo->find($id);
    $user->setDeleted(1); 
    $session->set('deleted', $user->getDeleted());
    $em->flush();
    return $this->redirectToRoute('showalladmin');
}
#[Route('/user/back/{id}/corbeille/enseignant', name: 'envoyerCorbeilleenseiang')]
public function envoyercorbeiiilleenseignant(UserRepository $repo, Request $request, EntityManagerInterface $em,int $id)
{
    $user =$repo->find($id);
    $user->setDeleted(1); 
    $em->flush();
    return $this->redirectToRoute('showallenseignant');
}
#[Route('/user/back/deletedetudiant', name: 'corbeilleetudiant')]
public function corbeilleetudiant(UserRepository $repo): Response
{
    $users = $repo->findBy([
        'roles' => "Etudiant",
        'deleted' => 1
    ]);

return $this->render('user/deletedetudiant.html.twig',[
  
    'user' =>$users
    ]);
}


#[Route('/user/back/deletedadmin', name: 'corbeilleadmin')]
public function corbeilleeadmin(UserRepository $repo): Response
{
    $users = $repo->findBy([
        'roles' => "Admin",
        'deleted' => 1
    ]);

return $this->render('user/deletedadmin.html.twig',[
  
    'user' =>$users
    ]);
}




#[Route('/user/back/{id}/restaureretudiant', name: 'restaureretudiant')]
public function restaureretudiant(UserRepository $repo, Request $request, EntityManagerInterface $em,int $id)
{
    $user =$repo->find($id);

    $user->setDeleted(0); 
    $em->flush();
    return $this->redirectToRoute('showalletudiant');
}
#[Route('/user/back/{id}/restaureadmin', name: 'restaureradmin')]
public function restaureadmin(UserRepository $repo, Request $request, EntityManagerInterface $em,int $id)
{
    $user =$repo->find($id);

    $user->setDeleted(0); 
    $em->flush();
    return $this->redirectToRoute('showalladmin');
}
#[Route('/user/back/{id}/restaurenseignant', name: 'restaurerenseignant')]
public function restaurerensei(UserRepository $repo, Request $request, EntityManagerInterface $em,int $id)
{
    $user =$repo->find($id);

    $user->setDeleted(0); 
    $em->flush();
    return $this->redirectToRoute('showallenseignant');
}


#[Route('/user/back/deletedenseignant', name: 'corbeillenseignant')]
public function corbeilleenseignant(UserRepository $repo): Response
{
    $users = $repo->findBy([
        'roles' => "Enseignant",
        'deleted' => 1
    ]);

return $this->render('user/eletedenseignant.html.twig',[
  
    'user' =>$users
    ]);
}


#[Route('/user/front/{id}/edit', name: 'edit_user')]
public function editfront(UserRepository $repo,Request $request, EntityManagerInterface $em,String $id,UserPasswordHasherInterface $passwordHasher,SessionInterface $session)
{
        
    if($session->get('id') != $id){
        return $this->redirectToRoute('logout');   
    }
    $user =$repo->find($id);
    $form = $this->createForm(UserType2::class, $user);
    $form->handleRequest($request);
   $image=$user->getImage();

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();
        if ($imageFile = $form->get('image')->getData() != null){
            $imageFile = $form->get('image')->getData();
            $filename= uniqid() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(
                $this->getParameter('upload_directory'),
                $filename
            );

        
        $user->setImage($filename);
        }
        else{
            $user->setImage($image); 
        }
        $em->persist($user);
         $em->flush();
         
         $session->set('image', $user->getImage());
         $session->set('email', $user->getEmail());
         $session->set('deleted', $user->getDeleted()); 
         $session->set('test', $user->getEmail());
         $session->set('user_nom', $user->getNom());
         $session->set('user_prenom', $user->getPrenom());
         $session->set('localisation', $user->getLocalisation());
       
        return $this->redirectToRoute('front'); 

    }
else{
    return $this->render('user/editfront.html.twig', [
        'form' => $form,
       
    ]);
}

}


#[Route('/user/showall/etudiant', name: 'showalletudiant')]
public function showalletudiant(UserRepository $repo): Response
{
    $user = $repo->findBy([
        'roles' => "Etudiant",
        'deleted' => 0
    ]);

    return $this->render('user/showetudiant.html.twig', [
        'user' => $user,
    ]);
}

#[Route('/user/showall/admin', name: 'showalladmin')]
public function showalladmint(UserRepository $repo): Response
{
    $user = $repo->findBy([
        'roles' => "Admin",
        'deleted' => 0
    ]);

    return $this->render('user/showadmin.html.twig', [
        'user' => $user,
    ]);
}



#[Route('/user/showall/enseignant', name: 'showallenseignant')]
public function showallenseignant(UserRepository $repo): Response
{
    $user = $repo->findBy([
        'roles' => "Enseignant",
        'deleted' => 0
    ]);


    return $this->render('user/showenseignant.html.twig', [
        'user' => $user,
    ]);
    
}
#[Route('/user/showall/admin', name: 'showalladmin')]
public function showalladmin(UserRepository $repo): Response
{
    $user = $repo->findBy([
        'roles' => "Admin",
        'deleted' => 0
    ]);


    return $this->render('user/showadmin.html.twig', [
        'user' => $user,
    ]);
    
}


#[Route('/user/back/{id}/one_etudiant', name: 'one_etudiant')]
public function one_etudiant_back(UserRepository $repo, Request $request,int $id): Response
{
   
    $user =$repo->find($id);
    if($user){
        return $this->render('user/detail_etudiant.html.twig', [
            'us' => $user 
        ]);
    }
    else{
        return $this->redirectToRoute('showalletudiant'); 
    }
   
}

#[Route('/user/back/{id}/one_admin', name: 'one_admin')]
public function one_admin(UserRepository $repo, Request $request,int $id): Response
{
   
    $user =$repo->find($id);
    if($user){
        return $this->render('user/detail_admin.html.twig', [
            'us' => $user 
        ]);
    }
    else{
        return $this->redirectToRoute('showalladmin'); 
    }
   

}


#[Route('/user/front/{id}/pagefront', name: 'pageeditfront')]
public function pagedtifront(UserRepository $repo, Request $request,int $id,SessionInterface $session): Response
{
    if($session->get('id') != $id){
        return $this->redirectToRoute('logout');   
    }
    $user =$repo->find($id);
    if($user){
        return $this->render('user/editfrontpage.html.twig', [
            'us' => $user 
        ]);
    }
    else{
        return $this->redirectToRoute('showalladmin'); 
    }
   
}

#[Route('/user/back/{id}/one_adminadmin', name: 'one_adminadmin')]
public function one_adminadmin(UserRepository $repo, Request $request,int $id): Response
{
   
    $user =$repo->find($id);
    if($user){
        return $this->render('user/detail_admin1.html.twig', [
            'us' => $user 
        ]);
    }
    else{
        return $this->redirectToRoute('back'); 
    }
   
}



#[Route('/user/back/{id}/one_enseignant', name: 'one_enseignant')]
public function one_enseignant_back(UserRepository $repo, Request $request,int $id): Response
{
   
    $user =$repo->find($id);
    if($user){
        return $this->render('user/detail_enseignant.html.twig', [
            'us' => $user 
        ]);
    }
    else{
        return $this->redirectToRoute('showallenseignant'); 
    }
   
}
#[Route('/user/back/{id}/one_enseignantback', name: 'one_enseignantback')]
public function one_enseignant_backenseignant(UserRepository $repo, Request $request,int $id): Response
{
   
    $user =$repo->find($id);
    if($user){
        return $this->render('user/detailbackenseignant.html.twig', [
            'us' => $user 
        ]);
    }
    else{
        return $this->redirectToRoute('backenseignant'); 
    }
   
}






#[Route('/user/back/{id}/edit', name: 'editback')]
public function editback(UserRepository $repo, Request $request, EntityManagerInterface $em,String $id,UserPasswordHasherInterface $passwordHasher,SessionInterface $session)
{
    if($session->get('id') != $id){
        return $this->redirectToRoute('logout');   
    }
    $user =$repo->find($id);
    $form = $this->createForm(UserType2::class, $user);
    $form->handleRequest($request);
   $image=$user->getImage();

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();
        if ($imageFile = $form->get('image')->getData() != null){
            $imageFile = $form->get('image')->getData();
            $filename= uniqid() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(
                $this->getParameter('upload_directory'),
                $filename
            );

        
        $user->setImage($filename);
        }
        else{
            $user->setImage($image); 
        }
        $em->persist($user);
         $em->flush();
         $session->set('image', $user->getImage());
         $session->set('email', $user->getEmail());
         
         $session->set('test', $user->getEmail());
         $session->set('user_nom', $user->getNom());
         $session->set('user_prenom', $user->getPrenom());
         $session->set('localisation', $user->getLocalisation());
       
        return $this->redirectToRoute('back'); 

    }
else{
    return $this->render('user/editback.html.twig', [   
        'form' => $form,
       
    ]);
}



}



#[Route('/user/back/createadmin', name: 'creatadmin1')]
  
public function createadmin(Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher,UserRepository $repo)
{

$user= new User();
$user->setRoles("Admin");
$user->setDeleted(0);
$user->setBanned(0);
$user->setGradeLevel(null);
$user->setSpecialite(0); 

$form =$this->createForm(UserType::class, $user);
$form->handleRequest($request);

if($form->isSubmitted()&& $form->isValid())
{
$test = $repo->findOneBy(['email' => $user->getEmail()]);
if ($user->getPassword() !== $user->getConfirmPassword() ) { 
    return $this->render('user/create.html.twig',[
        'form' => $form->createView(),
        ]);
 
}
if($test != null ){
     return $this->render('user/create.html.twig',[
        'form' => $form->createView(),
        ]);
}
$imageFile = $form->get('image')->getData();
        if ($imageFile = $form->get('image')->getData() != null){
            $imageFile = $form->get('image')->getData();
            $filename= uniqid() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(
                $this->getParameter('upload_directory'),
                $filename
            );

        
        $user->setImage($filename);
        }
else {
    $user->setImage('inconnu.jpg');  
}  
$pass = $user->getPassword();
$hashedPassword = password_hash($pass, PASSWORD_BCRYPT);
$user->setPassword($hashedPassword);
$user->setConfirmPassword($hashedPassword);
$em->persist($user);
$em->flush();

return $this->redirectToRoute('showalladmin');

}
else 
{

return $this->render('user/create.html.twig',[
'form' => $form->createView(),
]);
}

}



#[Route('/user/back/createetudiant', name: 'creatadmin')]
  
public function createetudiant(Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher,UserRepository $repo)
{

$user= new User();
$user->setRoles("Etudiant");
$user->setDeleted(0);
$user->setBanned(0);
$user->setGradeLevel(0);
$user->setSpecialite(0); 

$form =$this->createForm(UserType::class, $user);
$form->handleRequest($request);

if($form->isSubmitted()&& $form->isValid())
{
$test = $repo->findOneBy(['email' => $user->getEmail()]);
if ($user->getPassword() !== $user->getConfirmPassword() ) { 
    return $this->render('user/createetudiant.html.twig',[
        'form' => $form->createView(),
        ]);
 
}
if($test != null ){
     return $this->render('user/createetudiant.html.twig',[
        'form' => $form->createView(),
        ]);
}
$imageFile = $form->get('image')->getData();
        if ($imageFile = $form->get('image')->getData() != null){
            $imageFile = $form->get('image')->getData();
            $filename= uniqid() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(
                $this->getParameter('upload_directory'),
                $filename
            );

        
        $user->setImage($filename);
        }
else {
    $user->setImage('inconnu.jpg');  
}  
$pass = $user->getPassword();
$hashedPassword = $passwordHasher->hashPassword($user, $pass);
$user->setPassword($hashedPassword);
$em->persist($user);
$em->flush();

return $this->redirectToRoute('showalletudiant');

}
else 
{

return $this->render('user/createetudiant.html.twig',[
'form' => $form->createView(),
]);
}

}



#[Route('/user/back/createenseignant', name: 'createenseignanr')]
  
public function createnseignant(Request $request,EntityManagerInterface $em,UserPasswordHasherInterface $passwordHasher,UserRepository $repo)
{

$user= new User();
$user->setRoles("Enseignant");
$user->setDeleted(0);
$user->setBanned(0);
$user->setGradeLevel(0);

$form =$this->createForm(User1Type::class, $user);
$form->handleRequest($request);

if($form->isSubmitted()&& $form->isValid())
{
$test = $repo->findOneBy(['email' => $user->getEmail()]);
if ($user->getPassword() !== $user->getConfirmPassword() ) { 
    return $this->render('user/createenseignant.html.twig',[
        'form' => $form->createView(),
        ]);
 
}
if($test != null ){
     return $this->render('user/createenseignant.html.twig',[
        'form' => $form->createView(),
        ]);
}
$imageFile = $form->get('image')->getData();
        if ($imageFile = $form->get('image')->getData() != null){
            $imageFile = $form->get('image')->getData();
            $filename= uniqid() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(
                $this->getParameter('upload_directory'),
                $filename
            );

        
        $user->setImage($filename);
        }
else {
    $user->setImage('inconnu.jpg');  
}  
$pass = $user->getPassword();
$hashedPassword = $passwordHasher->hashPassword($user, $pass);
$user->setPassword($hashedPassword);
$em->persist($user);
$em->flush();

return $this->redirectToRoute('showallenseignant');

}
else 
{

return $this->render('user/createenseignant.html.twig',[
'form' => $form->createView(),
]);
}

}

























#[Route('/user/back/{id}/editenseignant', name: 'editbackenseignant')]
public function editbackenseignant(UserRepository $repo, Request $request, EntityManagerInterface $em,String $id,UserPasswordHasherInterface $passwordHasher,SessionInterface $session)
{
    if($session->get('id') != $id){
        return $this->redirectToRoute('logout');   
    }
    
    $user =$repo->find($id);
    $form = $this->createForm(UserType2::class, $user);
    $form->handleRequest($request);
   $image=$user->getImage();

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image')->getData();
        if ($imageFile = $form->get('image')->getData() != null){
            $imageFile = $form->get('image')->getData();
            $filename= uniqid() . '-' . $imageFile->getClientOriginalName();
            $imageFile->move(
                $this->getParameter('upload_directory'),
                $filename
            );

        
        $user->setImage($filename);
        }
        else{
            $user->setImage($image); 
        }
        $em->persist($user);
         $em->flush();
         $session->set('image', $user->getImage());
         $session->set('email', $user->getEmail());
         
         $session->set('test', $user->getEmail());
         $session->set('user_nom', $user->getNom());
         $session->set('user_prenom', $user->getPrenom());
         $session->set('localisation', $user->getLocalisation());
       
        return $this->redirectToRoute('backenseignant'); 

    }
else{
    return $this->render('user/editenseignantback.html.twig', [   
        'form' => $form,
       
    ]);
}



}




}
