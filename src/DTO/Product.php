<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Product as ProductEntity;

class Product
{
    protected string $title;
    
    protected int $price;
    
    protected array $categories = [];
    
    public function __construct(ProductEntity $product)
    {
        $this->title = $product->getName();
        $this->price = $product->getPrice();
        
        foreach ($product->getCategories() as $category) {
            $this->categories[] = $category->getName();
        }
    }
    
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'price' => $this->price,
            'categories' => $this->categories
        ];
    }
    
    public function __toString(): string
    {
        return '<product>' .PHP_EOL.
                '<title>'. $this->title . '</title>'. PHP_EOL.
                '<price>'. $this->price .'</price>' .PHP_EOL.
                '<categories>'. PHP_EOL.
                    $this->categoriesList().
                '</categories>'. PHP_EOL.
            '</product>'
        ;
    }
    
    protected function categoriesList(): string
    {
        $list = '';
        foreach ($this->categories as $category) {
            $list .= '<category>' . $category . '</category>'.PHP_EOL;
        }
        return $list;
    }
}