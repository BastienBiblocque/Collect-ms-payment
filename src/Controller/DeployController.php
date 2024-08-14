<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class DeployController extends AbstractController
{
    #[Route('/deploy', name: 'app_deploy', methods: ['POST'])]

    public function deploy(Request $request, EntityManagerInterface $entityManager, ShopRepository $shopRepository): Response
    {
        $higherShopPort = $shopRepository->findShopWithHighestPort();
        $higherPort =  $higherShopPort->getPort();
        $newPort = (int)$higherPort + 1;

        $data = $request->getContent();
        $data = json_decode($data, true);

        $shop = new Shop();
        $shop->setName($data['name']);
        $shop->setPort($newPort);
        $shop->setStatus('non deploy');
        $shop->setTheme($data['theme']);
        $shop->setTheme("cyberpunk");
        $shop->setShopId($data['id']);
        $entityManager->persist($shop);
        $entityManager->flush();

        // Chemin vers le script shell du projet React
        $scriptPath = '../../ChezEux-Site-Client/launch-instance.sh';

        // Donner les permissions d'exécution si nécessaire
        chmod($scriptPath, 0755);

        $scriptPath = '../../ChezEux-Site-Client/launch-instance.sh ' .  $shop->getShopId() . ' ' . $shop->getName() . ' '. $shop->getTheme() . ' ' . $shop->getPort() . ' videogaming';

        // Exécuter le script
        $output = shell_exec($scriptPath);

        $shop->setStatus('deploy');
        $entityManager->persist($shop);
        $entityManager->flush();
        return $this->json($shop, Response::HTTP_OK);

    }
}
