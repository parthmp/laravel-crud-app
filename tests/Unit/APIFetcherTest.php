<?php

namespace Tests\Unit;

use Tests\TestCase;

use \App\Custom\APIFetcher;


class APIFetcherTest extends TestCase
{
    
    /** @test */
    public function if_api_endpoint_and_api_key_has_set(){
        
        $fetcher = new APIFetcher();

        $this->assertTrue($fetcher->getEndpoint() != null && $fetcher->getEndpoint() != '');
        $this->assertTrue($fetcher->getKey() != null && $fetcher->getKey() != '');
        
        
    }


    /** @test */
    public function if_valid_response(){

        $fetcher = new APIFetcher();
        
        $code = $fetcher->fetchPage();

        if($code->getStatusCode() == 200){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }

    }

    /** @test */
    public function if_response_is_invalid_with_bad_auth(){

        $fetcher = new APIFetcher();

        $fetcher->setKey('');
        
        $code = $fetcher->fetchPage();

        if($code->getStatusCode() != 200){
            $this->assertTrue(true);
        }else{
            $this->assertTrue(false);
        }

    }


}
