<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Form\UpdateProductType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/createProduct', name: 'product')]
        public function create(Request  $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $x = $this->getDoctrine()->getManager();
        $x->persist($product);
        $x->flush();
            return $this->redirect($this->generateUrl('productList'));
        }
        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

//        $category = new Category();
//        $category->setName('Computer Peripherals');
//
//        $product = new Product();
//        $product->setName('Mouse');
//        $product->setPrice(20.33);
//
//        // relates this product to the category
//        $product->setCategory($category);
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $entityManager->persist($category);
//        $entityManager->persist($product);
//        $entityManager->flush();
//
//        return new Response(
//            'Saved new product with id: ' . $product->getId()
//            . ' and new category with id: ' . $category->getId()
//        );

    public  function __toString()
    {
       return $this->name;
    }

    /**
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/products', name: 'productList')]
    public function showAllProduct(Request $request,PaginatorInterface $paginator): Response
    {
        $productRepo = $this->getDoctrine()->getRepository(persistentObject: Product::class);
        $products = $productRepo->findAll();
        $products = $paginator ->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/productDelete', name: 'productDelete')]
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        $productDelete = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $product = $this->getDoctrine()->getManager();
        $product->remove($productDelete);
        $product->flush();
        return $this->redirect($this->generateUrl('productList'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/productEdit', name: 'productEdit')]
    public function edit(Request $request): Response
    {
        $id = $request->get('id');
        $productEdit = $this->getDoctrine()->getRepository(Product::class)->find($id);
        $form = $this->createForm(UpdateProductType::class, $productEdit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $x = $this->getDoctrine()->getManager();
            $x->persist($productEdit);
            $x->flush();
            return $this->redirect($this->generateUrl('productList'));
        }
        return $this->render('product/create.html.twig', [
            'form' => $form->createView()
        ]);

    }


}
