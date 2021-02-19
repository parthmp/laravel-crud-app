<?php

    namespace App\Custom;

    use \GuzzleHttp\Client;
    use \Illuminate\Support\Facades\Config;

    class APIFetcher{

        private $api_endpoint;
        private $api_key;
        private $per_page;
        private $page_num = 1;
        private $status_code;
        private $body;
        
        public function __construct(){
            $this->api_endpoint = Config::get('app.api_endpoint');
            $this->api_key = Config::get('app.api_key');
            $this->per_page = Config::get('app.per_page');
        }

        public function getEndpoint(){
            return $this->api_endpoint;
        }

        public function getKey(){
            return $this->api_key;
        }

        public function setKey($key){
            $this->api_key = $key;
        }

        public function setPerPage($page){
            $this->per_page = $page;
        }

        public function getPerPage(){
            return $this->per_page;
        }

        public function setPageNum($num){
            $this->page_num = $num;
        }

        public function getStatusCode(){
            return $this->status_code;
        }

        public function getBody(){
            return $this->body;
        }

        public function fetchPage(){

            $client = new Client(['base_uri' => $this->api_endpoint, 'timeout'  => 5.0]);
            
            $response = $client->request('GET', '/api/properties?api_key='.$this->api_key.'&page[size]='.$this->per_page.'&page[number]='.$this->page_num, ['http_errors' => false]);
            
            $this->status_code = $response->getStatusCode();
            
            if($this->status_code == 200){
                $this->body = $response->getBody()->getContents();
            }

            return $this;

        }

    }
