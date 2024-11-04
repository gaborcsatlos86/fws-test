<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\Product as ProductEntity;
use App\DTO\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response};
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $responseContent = '';
        
        foreach ($entityManager->getRepository(ProductEntity::class)->findAll() as $productEntity) {
            $productDto = new Product($productEntity);
            $responseContent .= (string)$productDto. PHP_EOL;
        }
        
        return new Response(content: '<products>'.$responseContent. '</products>', headers: ['Content-Type' => 'text/xml']);
    }
    
}