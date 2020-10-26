<?php
namespace app\components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;
use yii\httpclient\Exception;

class Instagram
{
    private $apiUrl;
    private $queryString;
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->apiUrl = "https://www.instagram.com/";
        $this->queryString  = "/?__a=1";
    }

    private function findUserByName(string $username): object
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl . $username . $this->queryString);
        } catch (GuzzleException $e) {
            return new stdClass();
        }
        return json_decode($response->getBody());
    }

    public function getUrlImageByUser(string $username): string
    {
        $user = $this->findUserByName($username);
        if(!isset($user->graphql)){
            return '';
        }
        return $user->graphql->user->profile_pic_url;
    }

    public function getPostsByUser(string $username): array
    {
        $posts = [];
        $user = $this->findUserByName($username);
        if(!isset($user->graphql)){
            return $posts;
        }

        foreach ($user->graphql->user->edge_owner_to_timeline_media->edges as $row)
        {
            $posts[] = new InstagramPost($row->node);
        }
        return $posts;
    }
}


