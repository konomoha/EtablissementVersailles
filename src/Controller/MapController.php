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
        $user = $this->getUser();

        if($user){
            return $this->render('map/index.html.twig', [
                'controller_name' => 'MapController',
            ]);
        }
        else{
            return $this->redirectToRoute('app_login');
        }
    }


    #[Route('/map/coordonnees', name: 'app_map_coordonnees')]
    public function coordonneeAll(Request $request): Response
    {
        $user = $this->getUser();

        if($user){
            $response = $this->client->request(
                'GET',
                'https://data.education.gouv.fr/api/v2/catalog/datasets/fr-en-annuaire-education/records?select=nom_etablissement%2C%20type_etablissement%2C%20latitude%2C%20longitude&where=nom_commune%20%3D%20%22Versailles%22%20and%20type_etablissement%20in%20%28%22Coll%C3%A8ge%22%2C%20%22Ecole%22%2C%20%22Lyc%C3%A9e%22%29&order_by=type_etablissement&limit=80&offset=0'
            );

            $content = $response->toArray();

            $listeCoordonnees = [];

            for($i = 0; $i < count($content['records']); $i++){
                $listeCoordonnees[]= ['latitude' => $content['records'][$i]['record']['fields']['latitude'], 'longitude' => $content['records'][$i]['record']['fields']['longitude'], 'type' => $content['records'][$i]['record']['fields']['type_etablissement'], 'nom' => $content['records'][$i]['record']['fields']['nom_etablissement']];
            }
            dump($listeCoordonnees);
            // $tab = json_encode($listeCoordonnees);

            return new JsonResponse($listeCoordonnees);
        }
        else{
            return $this->redirectToRoute('app_login');
        }
    }

    // #[Route('/map/{nom}', name: 'app_map_nom')]
    // public function coordonnee(Request $request)
    // {
    //     $nom = $request->get('nom');

    // }
}
