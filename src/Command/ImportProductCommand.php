<?php

declare(strict_types=1);

namespace App\Command;


use App\Entity\{Product, Category, NamedEntityInterface};
use App\Repository\NamedEntityRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\ORM\EntityManagerInterface;


#[AsCommand(name: 'app:import-product')]
class ImportProductCommand extends Command
{
    public function __construct(
        private ParameterBagInterface   $params,
        private EntityManagerInterface  $em,
        ?string                         $name = null
    ) {
        parent::__construct($name);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (($handle = fopen($this->params->get('kernel.project_dir').'/public/termekek.csv', 'r')) == false) {
            $output->writeln('Error!! No termekek.csv file in public folder, or not readable.');
            return Command::FAILURE;
        }
        $row = $created = 0;
        while (($rowData = fgetcsv($handle)) !== false) {
            if ($row == 0) {
                $row++;
                continue;
            } 
            if (!isset($rowData[0])) {
                $output->writeln('Error!! Invalid row, no product name at '. $row);
                fclose($handle);
                return Command::INVALID;
            }
            $isNew = false;
            /** @var Product $product */
            $product = $this->loadOrCreateEntity($rowData[0], Product::class, $isNew);
            if ($isNew) {
                $created++;
            }
            if (isset($rowData[1])) {
                $product->setPrice((int)$rowData[1]);
            }
            if ($product->getCategories()->count() > 0) {
                $product->getCategories()->clear();
            }
            for ($i=2; $i<5; $i++ ) {
                if (isset($rowData[$i])) {
                    $product->addCategory($this->loadOrCreateEntity($rowData[$i], Category::class, $isNew));
                }
            }
            $this->em->persist($product);
            $this->em->flush();
            $this->em->refresh($product);
            
            $row++;
            $product = null;
        }
        
        fclose($handle);
        $output->writeln((($row-1)-$created) . ' product updated, '. $created . ' product created');
        
        return Command::SUCCESS;
    }
    
    private function loadOrCreateEntity(string $name, string $className, bool &$isNew): NamedEntityInterface
    {
        if ( ($entity = $this->em->getRepository($className)->findOneBy(['name' => $name])) == null) {
            $isNew = true;
            $entity = (new $className())
                ->setName($name)
            ;
            $this->em->persist($entity);
            $this->em->flush();
            
            $this->em->refresh($entity);
        }
        return $entity;
    }
}
