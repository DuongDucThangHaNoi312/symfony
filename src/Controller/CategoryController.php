<?php
namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\UpdateCategoryType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method encodeUserDataToJson(object[] $category)
 */
class CategoryController extends AbstractController
{

    #[Route('/home', name: 'home')]
    public function home(): Response
   {
    return $this->render('product/Home.html.twig');
    }
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/createCategory', name: 'category')]
    public function create(Request $request): Response
    {
        $category= new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $x = $this->getDoctrine()->getManager();
            $x->persist($category);
            $x->flush();
            return $this->redirect($this->generateUrl('CategoryList'));
        }
        return $this->render('category/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/categories', name: 'CategoryList')]
    public function showAllProduct(Request $request,PaginatorInterface $paginator): Response
    {
        $categoryRepo = $this->getDoctrine()->getRepository(persistentObject: Category::class);
        $category = $categoryRepo->findAll();
        $category = $paginator ->paginate(
            $category, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return $this->render('category/listcategory.html.twig', parameters: [
            'category' => $category,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/categoryDelete', name: 'categoryDelete')]
    public function delete(Request $request): Response
    {
        $id = $request->get('id');
        $categoryDelete = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $category = $this->getDoctrine()->getManager();
        $category ->remove($categoryDelete);
        $category->flush();
        return $this->redirect($this->generateUrl('CategoryList'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/categoryEdit', name: 'categoryEdit')]
    public function edit(Request $request): Response
    {
        $id = $request->get('id');
        $categoryEdit = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $form = $this->createForm(UpdateCategoryType::class, $categoryEdit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $x = $this->getDoctrine()->getManager();
            $x->persist($categoryEdit);
            $x->flush();
            return $this->redirect($this->generateUrl('CategoryList'));

        }
        return $this->render('category/form.html.twig', [
            'form' => $form->createView()
        ]);

    }



//    /**
//     * @Route("cateJson", name="api_product_search")
//     */
//    public function viewAction()
//    {
//        $categoryRepo = $this->getDoctrine()->getRepository(persistentObject: Category::class);
//        $categorys = $categoryRepo->findAll();
//        $data = array();
//        foreach ($categorys as $key => $category ){
//           $data[$key]['id'] = $category->getId();
//            $data[$key]['name'] = $category->getName();
//        }
//
//        return new JsonResponse($data);
//    }

}

