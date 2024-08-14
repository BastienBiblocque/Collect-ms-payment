<?php

namespace App\Controller;

use App\Entity\Shop;
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

    public function runScript(): Response
    {

        // Chemin vers le script shell du projet React
        $scriptPath = '../../ChezEux-Site-Client/launch-instance.sh';

        // Donner les permissions d'exécution si nécessaire
        chmod($scriptPath, 0755);

        $scriptPath = '../../ChezEux-Site-Client/launch-instance.sh cyberpunk 3006 videogaming';

        // Exécuter le script
        $output = shell_exec($scriptPath);


        // Retourner la sortie du script
        return new Response('Script exécuté avec succès!<br>Sortie: <pre>' . $output . '</pre>');
    }
}
