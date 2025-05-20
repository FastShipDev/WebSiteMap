<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class MakeController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/make', name: 'app_make')]
    public function index(): Response
    {
        $packagesResponse = $this->client->request(
            'GET',
            'https://api.fastship.healthmax.fr/v1/api/package/get-all-packages'
        );

        $travelsResponse = $this->client->request(
            'GET',
            'https://api.fastship.healthmax.fr/v1/api/travels/get-all-travels'
        );

        $packages = $packagesResponse->toArray();
        $travels = $travelsResponse->toArray();

        return $this->render('make/index.html.twig', [
            'controller_name' => 'MakeController',
            'packages' => $packages['dataIsSucces']['packages'],
            'travels' => $travels['dataIsSucces']['travelsData']
        ]);
    }
}