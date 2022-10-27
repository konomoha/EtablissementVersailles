<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MapController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        return $this->render('map/index.html.twig', [
            'controller_name' => 'MapController',
        ]);
    }

    #[Route('/map/coordonnees', name: 'app_map_coordonnees')]
    public function coordonnée(Request $request): Response
    {
        $response = $this->client->request(
            'GET',
            'https://data.education.gouv.fr/api/v2/catalog/datasets/fr-en-annuaire-education/records?select=longitude%2C%20latitude%2C%20type_etablissement&where=nom_commune%3D%22Versailles%22%20and%20type_etablissement%20in%20%28%27Ecole%27%2C%20%27Coll%C3%A8ge%27%2C%20%27Lyc%C3%A9e%27%29&order_by=type_etablissement&limit=95&offset=0'
        );

        $content = $response->toArray();

        $listeCoordonnees = [];

        for($i = 0; $i < count($content['records']); $i++){
            $listeCoordonnees[]= ['latitude' => $content['records'][$i]['record']['fields']['latitude'], 'longitude' => $content['records'][$i]['record']['fields']['longitude'], 'type' => $content['records'][$i]['record']['fields']['type_etablissement']];
        }
        dump($listeCoordonnees);
        // $tab = json_encode($listeCoordonnees);

        return new JsonResponse($listeCoordonnees);

        // $response = $this->client->request(
        //     'GET',
        //     'https://data.education.gouv.fr/api/v2/catalog/datasets/fr-en-annuaire-education/records?select=nom_etablissement%2C%20type_etablissement&where=nom_commune%3D%22Versailles%22%20and%20type_etablissement%20in%20%28%27Ecole%27%2C%20%27Coll%C3%A8ge%27%2C%20%27Lyc%C3%A9e%27%29&order_by=type_etablissement&limit=95&offset=0'
        // );

        // $content = $response->getContent();
        // // $content = '{"id":521583, "name":"symfony-docs", ...}'
        // $content2 = $response->toArray();
        // // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        // $listeEtablisements = [];

        // for($i = 0; $i < count($content2['records']); $i++){

        //     $listeEtablisements[]= ['nom' => $content2['records'][$i]['record']['fields']['nom_etablissement'], 'type' => $content2['records'][$i]['record']['fields']['type_etablissement']];
        // }

        // return new JsonResponse($listeEtablisements);
    }
}
