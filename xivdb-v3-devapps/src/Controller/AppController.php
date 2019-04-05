<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\App;
use App\Repository\AppRepository;
use XIV\Services\Email;
use XIV\XivService;

class AppController extends Controller
{
    /** @var XivService */
    private $xivService;
    
    public function __construct()
    {
        $this->xivService = new XivService();
    }
    
    /**
     * @Route(
     *     "/{app}",
     *     methods="GET",
     *     requirements={"app": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function index(App $app)
    {
        return $this->json($app);
    }
    
    /**
     * @Route(
     *     "/search",
     *     methods="GET"
     * )
     */
    public function search(Request $request)
    {
        /** @var AppRepository $repo */
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(App::class);
        
        return $this->json($repo->search($request));
    }
    
    /**
     * @Route(
     *     "/{app}",
     *     methods="PUT",
     *     requirements={"app": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function update(Request $request, App $app)
    {
        $json = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();
        
        $app
            ->setTitle($json->title ?? $app->getTitle())
            ->setDescription($json->description ?? $app->getDescription())
            ->setIcon($json->icon ?? $app->getIcon())
            ->setUrl($json->url ?? $app->getUrl())
            ->setLanguage($json->language ?? $app->getLanguage())
            ->setDevice($json->device ?? $app->getDevice())
            ->setUpdated();
        
        $em->persist($app);
        $em->flush();
        
        return $this->json($app);
    }
    
    /**
     * @Route(
     *     "/{app}",
     *     methods="DELETE",
     *     requirements={"app": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function delete(App $app)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($app);
        $em->flush();
        
        return $this->json([
            'status' => true,
        ]);
    }
    
    /**
     * @Route(
     *     "/",
     *     methods="POST"
     * )
     */
    public function create(Request $request)
    {
        $json = json_decode($request->getContent());
        $em = $this->getDoctrine()->getManager();
    
        $app = new App();
        $app->setUserId($json->userId)
            ->setTitle($json->title)
            ->setDescription($json->description);

        // create a data api key
        $app->addKey(
            $this->xivService->Data->createKey(
                $json->title,
                $json->userId
            )
        );

        $em->persist($app);
        $em->flush();
        
        $this->xivService->Email->send(
            Email::DEV,
            "New App: {$app->getTitle()}",
            "devapp_new",
            [
                'devapp' => $app->data()
            ]
        );

        return $this->json($app);
    }
}
