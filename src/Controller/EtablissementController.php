<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EtablissementController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home.html.twig', [
        ]);
    }


    #[Route('/liste', name: 'app_liste')]
    public function listeEtablissements(): Response
    {
        $response = $this->client->request(
            'GET',
            'https://data.education.gouv.fr/api/v2/catalog/datasets/fr-en-annuaire-education/records?select=nom_etablissement%2C%20type_etablissement&where=nom_commune%3D%22Versailles%22%20and%20type_etablissement%20in%20%28%27Ecole%27%2C%20%27Coll%C3%A8ge%27%2C%20%27Lyc%C3%A9e%27%29&order_by=type_etablissement&limit=95&offset=0'
        );

        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content2 = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        $listeEtablisements = [];

        for($i = 0; $i < count($content2['records']); $i++){

            $listeEtablisements[]= ['nom' => $content2['records'][$i]['record']['fields']['nom_etablissement'], 'type' => $content2['records'][$i]['record']['fields']['type_etablissement']];
        }

        return $this->render('etablissement/index.html.twig', [
            "listeEtablissements" => $listeEtablisements
        ]);
    }

    #[Route('/test', name: 'app_test')]
    public function about(Request $request)
    {
        $response = $this->client->request(
            'GET',
            'https://data.education.gouv.fr/api/v2/catalog/datasets/fr-en-annuaire-education/records?select=nom_etablissement%2C%20type_etablissement%2C%20latitude%2C%20longitude&where=nom_commune%20%3D%20%22Versailles%22%20and%20type_etablissement%20in%20%28%22Coll%C3%A8ge%22%2C%20%22Ecole%22%2C%20%22Lyc%C3%A9e%22%29&order_by=type_etablissement&limit=80&offset=0'
        );

        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content2 = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        $listeEtablisements = [];

        for($i = 0; $i < count($content2['records']); $i++){

            $listeEtablisements[]= ['nom' => $content2['records'][$i]['record']['fields']['nom_etablissement'], 'type' => $content2['records'][$i]['record']['fields']['type_etablissement']];
        }

        return new JsonResponse($listeEtablisements);
     }

}