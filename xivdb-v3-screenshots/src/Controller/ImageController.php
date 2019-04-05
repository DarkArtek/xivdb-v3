<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use App\Service\UploadService;

class ImageController extends Controller
{

    /**
     * @Route("/", methods="POST")
     */
    public function create(Request $request, UploadService $upload)
    {
        try {
            // handle upload
            $upload = (Object)$upload->save($request);

            // create image
            $image = new Image();
            $image
                ->setUserId($request->get('userId'))
                ->setIdUnique($request->get('idUnique'))
                ->setFilename($upload->filename)
                ->setFilenameThumbnail($upload->filenameThumbnail)
                ->setFilenameOriginal($upload->filenameOriginal)
                ->setDirectory($upload->directory)
                ->setHash($upload->hash)
                ->setMeta($upload->meta);

            // save image entity
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();
        } catch (\Exception $ex) {
            return $this->json([
                'error' => $ex->getMessage()
            ]);
        }

        return $this->json($image);
    }

    /**
     * @route("/list", methods="GET")
     */
    public function list(Request $request, ImageRepository $repo)
    {
        $images = $repo->findBy(
            [ 'idUnique' => $request->get('idUnique') ],
            [ 'added' => 'desc' ],
            100
        );

        if (!$images) {
            return $this->json([ 'error' => 'No images' ]);
        }

        return $this->json($images);
    }

    /**
     * @Route(
     *     "/{image}",
     *     methods="GET",
     *     requirements={"id": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function image(Request $request, Image $image, UploadService $upload)
    {
        $file = $upload->getFullFilePath(
            $image->getDirectory(),
            $request->get('thumb')
                ? $image->getFilenameThumbnail()
                : $image->getFilename()
        );

        // add view
        $image->hit();
        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();

        return new BinaryFileResponse($file);
    }

    /**
     * @Route(
     *     "/{image}/meta",
     *     methods="GET",
     *     requirements={"id": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function meta(Image $image)
    {
        return $this->json($image);
    }

    /**
     * @Route(
     *     "/{image}",
     *     methods="DELETE",
     *     requirements={"id": "[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}"}
     * )
     */
    public function delete(Image $image, UploadService $upload)
    {
        $filename = $upload->getFullFilePath($image->getDirectory(), $image->getFilename());
        $filenameThumbnail = $upload->getFullFilePath($image->getDirectory(), $image->getFilenameThumbnail());

        unlink($filename);
        unlink($filenameThumbnail);

        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        return $this->json(true);
    }
}
