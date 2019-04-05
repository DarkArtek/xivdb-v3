<?php

namespace App\Controller;

use Lodestone\Api;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Routing\Annotation\Route,
    Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Controller
 * @Route("/lodestone")
 */
class LodestoneController extends Controller
{
    /** @var Api */
    private $api;

    public function __construct()
    {
        $this->api = new Api();
    }

    /**
     * @Route("/banners", name="lodestone_banners", methods="GET")
     */
    public function banners()
    {
        return $this->json(
            $this->api->getLodestoneBanners()
        );
    }

    /**
     * @Route("/news", name="lodestone_news", methods="GET")
     */
    public function news()
    {
        return $this->json(
            $this->api->getLodestoneNews()
        );
    }

    /**
     * @Route("/topics", name="lodestone_topics", methods="GET")
     */
    public function topics()
    {
        return $this->json(
            $this->api->getLodestoneTopics()
        );
    }

    /**
     * @Route("/notices", name="lodestone_notices", methods="GET")
     */
    public function notices()
    {
        return $this->json(
            $this->api->getLodestoneNotices()
        );
    }

    /**
     * @Route("/maintenance", name="lodestone_maintenance", methods="GET")
     */
    public function maintenance()
    {
        return $this->json(
            $this->api->getLodestoneMaintenance()
        );
    }

    /**
     * @Route("/updates", name="lodestone_updates", methods="GET")
     */
    public function updates()
    {
        return $this->json(
            $this->api->getLodestoneUpdates()
        );
    }

    /**
     * @Route("/status", name="lodestone_status", methods="GET")
     */
    public function status()
    {
        return $this->json(
            $this->api->getLodestoneStatus()
        );
    }

    /**
     * @Route("/world-status", name="lodestone_world_status", methods="GET")
     */
    public function worldStatus()
    {
        return $this->json(
            $this->api->getWorldStatus()
        );
    }

    /**
     * @Route("/dev-blog", name="lodestone_dev_blog", methods="GET")
     */
    public function devBlogAction()
    {
        return $this->json(
            $this->api->getDevBlog()
        );
    }

    /**
     * @Route("/leaderboards/feast", name="lodestone_leaderboards_feast", methods="GET")
     */
    public function leaderboardFeast(Request $request)
    {
        return $this->json(
            $this->api->getFeast($request->get('season') ?: 1, $request->request->all())
        );
    }

    /**
     * @Route("/leaderboards/deep-dungeon", name="lodestone_leaderboards_deep_dungeon", methods="GET")
     */
    public function leaderboardDeepDungeon(Request $request)
    {
        return $this->json(
            $this->api->getDeepDungeon($request->request->all())
        );
    }

    /**
     * @Route("/forums/dev-posts", name="lodestone_forums_dev_posts", methods="GET")
     */
    public function forumDevPosts(Request $request)
    {
        return $this->json(
            $this->api->getDevPosts()
        );
    }
}
