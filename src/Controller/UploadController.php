<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\UploadType;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    /**
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    #[Route('/upload', name: 'upload')]
    public function Up(Request $request ,FileUploader $fileUploader): Response
    {
        $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
         $x = $this->getDoctrine()->getManager();
            $file = $request->files->get('upload')['image'];
            if ($file) {
               $filename = $fileUploader->uploadFile($file);
                $upload->setImage($filename);
                $x->persist($upload);
                $x->flush();
            }
            return $this->redirect($this->generateUrl('uploadList'));
        }
        return $this->render('upload/form.html.twig', [
            'form' => $form->createView()
        ]);



    }


    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/uploadList', name: 'uploadList')]
    public function Show(Request $request): Response
    {
        $uploadRepo = $this->getDoctrine()->getRepository(persistentObject: Upload::class);
        $uploads = $uploadRepo->findAll();
        return $this->render('upload/listupload.html.twig', parameters: [
            'upload' => $uploads,
        ]);
    }
}
