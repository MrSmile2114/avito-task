<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ItemController extends AbstractController
{
    private $allowedOptResFields = ['description', 'imgLinks', 'created', 'imgLinksArr'];

    private $defaultResponseFields = ['name', 'price', 'mainImgLink', 'id'];

    /**
     * @Route("/item/{item_id}", name="item", requirements={"item_id": "\d+"})
     * @param ItemRepository $repository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param int $item_id
     * @return JsonResponse
     */
    public function getItem(ItemRepository $repository, SerializerInterface $serializer, Request $request,
                            $item_id): JsonResponse
    {
        if (is_null($item_id) or $item_id <= 0 or $item_id > PHP_INT_MAX) {
            return $this->json([
                'status' => 'Invalid id',
                'item_id' => $item_id], 400);
        } else {
            $item = $repository->findWithCache($item_id);
            if (is_null($item)){
                return $this->json([
                    'status' => 'No item found with this id',
                    'item_id' => $item_id], 404);
            }
            $optionalFields = $request->get('fields', '');
            $itemData = [];
            foreach ($serializer->normalize($item) as $itemField => $itemFieldValue) {
                if (in_array($itemField, $this->defaultResponseFields) or
                    (in_array($itemField, $this->allowedOptResFields) and (strpos($optionalFields, $itemField) !== false))) {
                    $itemData[$itemField] = $itemFieldValue;
                }
            }
            return $this->json([
                'status' => 'Success',
                'item' => $itemData,
            ]);
        }
    }

    /**
     * @Route("/item/create", name="item_create", methods={"POST"})
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createItem(Request $request): JsonResponse
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->submit($request->request->all());
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            $em->clear();
            return $this->json([
                'status' => 'Success',
                'itemId' => $item->getId()]);
        } else {
            return $this->json([
                'status' => 'Validation error occurred',
                'errors' => $this->getErrorsFromForm($form)], 400);
        }


    }

    private function getErrorsFromForm(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
