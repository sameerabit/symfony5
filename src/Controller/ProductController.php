<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Service\DiscountCalculator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{

    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    public function new(Request $request,DiscountCalculator $discountCalculator) // dependency injection example
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $task = $form->getData();
            $task->setPrice($discountCalculator->calculate($task->getPrice()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_products_index');
        }
        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function show(Product $product)
    {
        return new Response('Product '.$product->getName());
    }

   
    public function update(Request $request,Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        if (!$product) { // this exception is handled through throwing not found exception
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
        }
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_products_index');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
}
