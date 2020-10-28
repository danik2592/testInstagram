<?php
namespace app\components;

use stdClass;

class Instagram
{
    private $apiUrl;
    private $queryString;
    private $session;
    private $data;

    public function __construct($username, $password)
    {
        $this->apiUrl = "https://www.instagram.com/";
        $this->queryString  = "/?__a=1";
        $this->session = [
            'header' => [
                'user-agent' => 'Instagram 10.3.2 Android (18/4.3; 320dpi; 720x1280; Huawei; HWEVA; EVA-L19; qcom; en_US)',
                'x-ig-app-id' => 1217981644879628
            ],
            'cookie' => []
        ];
        $this->data = http_build_query([
            'username' => $username,
            'password' => $password,
            'queryParams' => '{}',
            'optIntoOneTap' => false
        ]);
        $this->login();
    }

    private function curlRequest(string $url, $data = null, $isPost = false): array
    {
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        if ($isPost) {
            curl_setopt($cURLConnection, CURLOPT_POST, true);
        }
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HEADER, true);
        curl_setopt($cURLConnection, CURLOPT_TIMEOUT, 65);
        curl_setopt($cURLConnection, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURLConnection, CURLOPT_AUTOREFERER, false);
        curl_setopt($cURLConnection, CURLINFO_HEADER_OUT, true);
        $cookie = "";
        $headers = [];
        foreach ($this->session['header'] as $key => $value) {
            $headers[] = $key.":".$value;
        }
        foreach ($this->session['cookie'] as $key => $value) {
            $cookie.= "{$key}={$value}; ";
        }
        $headers[] = 'cookie:'.$cookie;
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headers);
        if ($data) {
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $this->data);
        }
        $response = curl_exec($cURLConnection);
        $headers  = curl_getinfo($cURLConnection);
        $httpCode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
        curl_close($cURLConnection);
        $headerContent = substr($response, 0, $headers['header_size']);
        $response = trim(str_replace($headerContent, '', $response));
        return [$response, $headers, $httpCode, $headerContent];
    }

    private function login(): void
    {
        list($response, $headers, $code, $headerContent) = $this->curlRequest('https://www.instagram.com');
        preg_match_all("/Set-Cookie:\s*(?<cookie>[^=]+=[^;]+)/mi", $headerContent, $matches);
        foreach ($matches['cookie'] as $c) {
            if ($c = str_replace(['sessionid=""', 'target=""'], '', $c)) {
                $c = explode('=', $c);
                $this->session['cookie'] = array_merge($this->session['cookie'], [trim($c[0]) => trim($c[1])]);
            }
        }
        $this->session['header']['x-csrftoken'] = $this->session['cookie']['csrftoken'] ?? $this->session['header']['x-csrftoken'] ?? '';
        $this->session['header']['content-type'] = 'application/x-www-form-urlencoded';
        $this->curlRequest($this->apiUrl.'accounts/login/ajax/', $this->data, true);
    }


    private function findUserByName(string $username): object
    {
        list($response, $headers, $code) = $this->curlRequest( $this->apiUrl . $username . $this->queryString);
        if($code === 200) {
            return \json_decode($response);
        }
        return new stdClass();
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


