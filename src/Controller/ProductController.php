<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {
    #[Route('/welcome', name: 'app_product')]
    public function index(): JsonResponse {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    #[Route('/products', methods: 'GET')]
    public function getProducts(ProductRepository $productRepository): JsonResponse {
        $products = $productRepository->findAll();
        return $this->json($products);
    }

    #[Route('/products', methods: 'POST')]
    public function createProduct(Request $request, EntityManagerInterface $em): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $em->persist($product);
        $em->flush();
        return $this->json($data);
    }

    #[Route('/removeId', methods: 'POST')]
    public function removeProduct(Request $request, EntityManagerInterface $em, ProductRepository $repository): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $product = $repository->find($data['id']);
        $em->remove($product);
        $em->flush();
        return $this->json($data);
    }

    #[Route('/getById', methods: 'POST')]
    public function getProductById(Request $request, ProductRepository $repository): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $product = $repository->find($data['id']);
        return $this->json($product);
    }
}
