<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\CharacterSettings;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\User\UserService;
use App\Service\SSO\CsrfInvalidException;
use App\Service\SSO\DiscordSignIn;
use XIV\XivService;

class AccountController extends Controller
{
    /** @var UserService */
    private $userService;
    /** @var Session */
    private $session;
    
    public function __construct(UserService $userService, SessionInterface $session)
    {
        $this->userService = $userService;
        $this->session     = $session;
    }
    
    /**
     * @Route("/account", name="account_index")
     */
    public function indexAccounts()
    {
        return $this->render('account/index.accounts.twig');
    }
    
    /**
     * @Route("/account/delete", name="account_delete")
     */
    public function indexDeleteAccount(Request $request)
    {
        $user = $this->userService->getUser();
    
        if (!$user) {
            throw new NotAcceptableHttpException();
        }
    
        if ($request->isMethod('POST')) {
            $pass = [
                $request->get('delete1') ? 1 : 0,
                $request->get('delete2') ? 1 : 0,
                $request->get('delete3') ? 1 : 0,
                $request->get('delete4') ? 1 : 0,
                $request->get('delete5') ? 1 : 0,
                $request->get('delete6') ? 1 : 0
            ];
            
            if (array_sum($pass) === 6) {
                $this->userService->deleteAccount();
                return $this->redirectToRoute('account_logout');
            }
    
            $this->session->getFlashBag()->set('account_deletion_error', true);
        }
        
        return $this->render('account/index.delete.twig');
    }
    
    /**
     * @Route("/account/download", name="account_download")
     */
    public function indexDownloadInformation(Request $request)
    {
        $user = $this->userService->getUser();
        
        if (!$user) {
            throw new NotAcceptableHttpException();
        }
        
        if ($request->get('request')) {
            $response = new Response($this->userService->downloadInformation() , 200);
            $response->headers->set('Content-Type', 'text/plain');
            return $response;
        }
        
        return $this->render('account/index.download.twig');
    }
    
    /**
     * @Route("/account/star", name="account_star")
     */
    public function indexStar()
    {
        return $this->render('account/index.star.twig');
    }
    
    // -----------------------------------------------------------------------------------
    
    /**
     * @Route("/account/characters", name="account_characters")
     */
    public function characters()
    {
        return $this->render('account/characters.twig');
    }
    
    /**
     * @Route("/account/characters/add")
     */
    public function charactersAdd(Request $request)
    {
        $sdk = new XivService();
        
        // check if name is a lodestone url
        $name = $request->get('name');
        
        // if lodestone url or name is numeric
        if (stripos($name, 'finalfantasyxiv.com') || is_numeric($name)) {
            $id = is_numeric($name) ? $name : explode('/', $name)[5];
            $character = $sdk->XIVSYNC->get('/character/parse/'. $id);
            
            return $this->json([
                'id'     => $character->id,
                'name'   => $character->name,
                'server' => $character->server,
                'avatar' => $character->avatar,
            ]);
        } else {
            $search = $sdk->XIVSYNC->get('/character/search', [
                'name'      => $request->get('name'),
                'server'    => $request->get('server'),
            ]);
    
            // if characters found
            if ($search->total > 0) {
                // attempt to match the name
                foreach ($search->characters as $character) {
                    if ($character->name == $request->get('name')) {
                        return $this->json([
                            'id'     => $character->id,
                            'name'   => $character->name,
                            'server' => $character->server,
                            'avatar' => $character->avatar,
                        ]);
                    }
                }
            }
        }
        
        return $this->json([
            'error' => 'Your character could not be found, try entering in the full Lodestone URL in the Name field.'
        ]);
    }
    
    /**
     * @Route("/account/characters/confirm")
     */
    public function charactersVerification(Request $request)
    {
        $user = $this->userService->getUser();
        
        // user not logged in
        if (!$user) {
            return $this->json([
                'error' => 'You do not seem to be logged in or your session has timed out, refresh the page and try again.'
            ]);
        }
        
        // ensure the character has been added to the site
        /** @var Character $exists */
        $exists = $this->getDoctrine()->getRepository(Character::class)->findOneBy([ 'id' => $request->get('id') ]);
        
        // character not processed
        if (!$exists) {
            return $this->json([
                'error' => 'Your character has not yet been processed through the XIVSYNC system just yet, try again in a few minutes.'
            ]);
        }
        
        
        // try get the character
        $character = (new XivService())->XIVSYNC->get('/character/parse/'. $request->get('id'));
        
        // character not found
        if (!$character) {
            return $this->json([
                'error' => 'Your character could not be found during the verification process, try searching again.'
            ]);
        }
        
        // biography not found
        if (stripos($character->biography, $user->getCharacterHash()) === false) {
            return $this->json([
                'error' => 'Your character verification code could not be found on your profile.'
            ]);
        }
        
        // attach character to user
        $exists->setUser($user)->setMain( !count($user->getCharacters()) );
        $this->getDoctrine()->getManager()->persist($exists);
        $this->getDoctrine()->getManager()->flush();
        
        return $this->json([
            'success' => 'Your character has been confirmed and linked to your account, you may refresh to see the changes.'
        ]);
    }
    
    /**
     * @Route("/account/characters/settings/{character}", name="account_characters_settings")
     */
    public function charactersSettings(Character $character, Request $request)
    {
        /** @var User $user */
        $user = $this->userService->getUser();
        
        if ($user->getId() !== $character->getUser()->getId()) {
            throw new NotAcceptableHttpException();
        }
        
        $settings = $character->getSettings() ?: new CharacterSettings();
        
        $form = $this->createFormBuilder($settings)
            ->add('hidden', CheckboxType::class, [
                'required' => false,
                'label' => 'Hide my entire character',
                'help'  => 'Your character will only be accessible by you, this means visitors or bots cannot see your character'
            ])
            ->add('hiddenClassJobs', CheckboxType::class, [
                'required' => false,
                'label' => 'Hide my Class/Jobs',
            ])
            ->add('hiddenGearsets', CheckboxType::class, [
                'required' => false,
                'label' => 'Hide my Gearsets',
            ])
            ->add('hiddenEvents', CheckboxType::class, [
                'required' => false,
                'label' => 'Hide my EXP/Level timeline events',
            ])
            ->add('hiddenTracking', CheckboxType::class, [
                'required' => false,
                'label' => 'Hide my Tracker timeline events',
                'help'  => 'This includes your name changes, server jumps and other profile changes'
            ])
            ->add('hiddenCollectables', CheckboxType::class, [
                'required' => false,
                'label' => 'Hide my Collectables',
                'help'  => 'This includes your minions and your mounts'
            ])
            ->add('hiddenAchievements', CheckboxType::class, [
                'required' => false,
                'label' => 'Hide my Achievements',
                'help'  => 'You can also hide this on your Lodestone profile'
            ])
            ->add('hiddenFriends', CheckboxType::class, [
                'required' => false,
                'label' => 'Hide your Friends List'
            ])
            ->add('stopUpdating', CheckboxType::class, [
                'required' => false,
                'label' => 'Stop character updates',
                'help'  => 'If you stop your character from updating you will loose any progress gained for the entire duration.'
            ])
            ->add('save', SubmitType::class)
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $character->setSettings($settings);
            $this->getDoctrine()->getManager()->persist($settings);
            $this->getDoctrine()->getManager()->flush();
            
            $this->session->getFlashBag()->set('character_settings_updated', true);
            
            return $this->redirectToRoute('account_characters_settings', [
                'character' => $character->getId(),
            ]);
        }
        
        return $this->render('account/characters.settings.twig', [
            'character' => $character,
            'form' => $form->createView(),
        ]);
    }
    
    // -----------------------------------------------------------------------------------
    
    /**
     * @Route("/account/comments", name="account_comments")
     */
    public function comments()
    {
        return $this->render('account/comments.twig');
    }
    
    /**
     * @Route("/account/screenshots", name="account_screenshots")
     */
    public function screenshots()
    {
        return $this->render('account/screenshots.twig');
    }
    
    /**
     * @Route("/account/login", name="account_login")
     */
    public function login()
    {
        return $this->render('account/login.twig');
    }
    
    /**
     * @Route("/account/logout", name="account_logout")
     */
    public function logout()
    {
        $this->userService->deleteCookie();
        return $this->redirectToRoute('index');
    }
    
    /**
     * @Route("/account/banned", name="account_banned")
     */
    public function banned()
    {
        $user = $this->userService->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('index');
        }
        
        $this->userService->deleteCookie();
    
        return $this->render('account/banned.twig', [
            'username'  => $user->getUsername(),
            'banned'    => $user->getBanned()
        ]);
    }
    
    /**
     * @Route("/account/error", name="account_error")
     */
    public function error()
    {
        $this->userService->deleteCookie();
        return $this->render('account/error.twig');
    }
    
    // -----------------------------------------------------------------------------------
    
    /**
     * @Route("/account/login/discord", name="account_login_discord")
     */
    public function loginDiscord(Request $request)
    {
        $url = $this->userService->setSsoProvider(new DiscordSignIn($request))->signIn();
        return $this->redirect($url);
    }
    
    /**
     * @Route("/account/login/discord/success", name="account_login_discord_success")
     */
    public function loginDiscordResponse(Request $request)
    {
        try {
            $user = $this->userService->setSsoProvider(new DiscordSignIn($request))->authenticate();
        } catch (CsrfInvalidException $ex) {
            return $this->redirectToRoute('account_error');
        }
        
        // if banned, redirect
        if ($user->isBanned()) {
            return $this->redirectToRoute('account_banned');
        }
        
        return $this->redirectToRoute('index');
    }
}
