<?php

namespace MyOrleansBundle\Controller\admin;

use MyOrleansBundle\Entity\Article;
use MyOrleansBundle\Entity\FileArticle;
use MyOrleansBundle\Entity\Media;
use MyOrleansBundle\Entity\Tag;
use MyOrleansBundle\Form\ArticleType;
use MyOrleansBundle\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Article controller.
 *
 * @Route("admin/article")
 * @Security("has_role('ROLE_ADMIN')")
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/", name="admin_article_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('MyOrleansBundle:Article')->findAll();

        /**
         * @var $pagination "Knp\Component\Pager\Paginator"
         * */
        $pagination = $this->get('knp_paginator');
        $results = $pagination->paginate(
            $articles,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('article/index.html.twig', array(
            'articles' => $results,
        ));
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/new", name="admin_article_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $article = new Article();
        $media = new Media();
        $article->getMedias()->add($media);
        $tag = new Tag();
        $article->getTags()->add($tag);

        $form = $this->createForm('MyOrleansBundle\Form\ArticleType', $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($article);
            $em->flush();

            if ($file = $form['fichierAssocie']->getData()){

            $fileArticle = new FileArticle();
            $fileArticle->setFile($file);
            $fileArticle->setArticle($article);
            $fileArticle->setName($file);
            $fileArticle->setPath($fileArticle->getWebPath().$fileArticle->getName());
            $fileArticle->upload();

            $em->persist($fileArticle);
            $em->flush();


            $this->addFlash('success', 'Votre article a bien été ajoutée');
            }
            return $this->redirectToRoute('admin_article_index', array('id' => $article->getId()));
        }

        return $this->render('article/new.html.twig', array(
            'article' => $article,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/{id}", name="admin_article_show")
     * @Method("GET")
     */
    public function showAction(Article $article)
    {
        $deleteForm = $this->createDeleteForm($article);

        return $this->render('article/show.html.twig', array(
            'article' => $article,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/edit", name="admin_article_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Article $article, FileUploader $fileUploader)
    {
        $deleteForm = $this->createDeleteForm($article);
//        if (!empty($article->getMedias())) {
//            $media = new Media();
//            $article->getMedias()->add($media);
//        }
//
//        if(!empty($article->getTags())) {
//            $tag = new Tag();
//            $article->getTags()->add($tag);
//        }

        $editForm = $this->createForm(ArticleType::class, $article);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre résidence a bien été mis à jour');
            return $this->redirectToRoute('admin_article_index', array('id' => $article->getId()));
        }

        return $this->render('article/edit.html.twig', array(
            'article' => $article,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/{id}", name="admin_article_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Article $article)
    {
        $form = $this->createDeleteForm($article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }
        $this->addFlash('danger', 'Votre article a bien été supprimée');

        return $this->redirectToRoute('admin_article_index');
    }


    /**
     * Deletes a article media.
     *
     * @Route("/{id}/delete_media/{media_id}", name="article_media_delete")
     * @ParamConverter("article", class="MyOrleansBundle:Article", options={"id" = "id"})
     * @ParamConverter("media", class="MyOrleansBundle:Media", options={"id" = "media_id"})
     * @Method({"GET", "POST"})
     */
    public function deleteMedia(Article $article, Media $media)
    {
        //$articles = $media->getArticles();
        $em = $this->getDoctrine()->getManager();

        $path = $media->getLien();
        unlink($this->getParameter('upload_directory') . '/' . $path);
        $article->removeMedia($media);
        $em->remove($media);

        $em->flush();
        return $this->redirectToRoute('admin_article_edit', array('id' => $article->getId()));
    }


    /**
     * Deletes a article tag.
     *
     * @Route("/{id}/delete_tag/{tag_id}", name="article_tag_delete")
     * @ParamConverter("article", class="MyOrleansBundle:Article", options={"id" = "id"})
     * @ParamConverter("tag", class="MyOrleansBundle:Tag", options={"id" = "tag_id"})
     * @Method({"GET", "POST"})
     */
    public function deleteTagAction(Article $article, Tag $tag)
    {
        //$articles = $media->getArticles();
        $em = $this->getDoctrine()->getManager();

        $path = $tag->getNom();
        $article->removeTag($tag);
        $em->remove($tag);

        $em->flush();
        return $this->redirectToRoute('admin_article_edit', array('id' => $article->getId()));
    }

    /**
     * Creates a form to delete a article entity.
     *
     * @param Article $article The article entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Article $article)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_article_delete', array('id' => $article->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
