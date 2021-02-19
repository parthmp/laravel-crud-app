<?php

    namespace App\Custom;

    use \GuzzleHttp\Client;
    use \Illuminate\Support\Facades\Config;
    use \App\Models\Sync;
    use \App\Models\PropertyType;
    use \App\Models\Property;

    class APIUpdate{

        public function prepare($log = false){
            if($log){
                echo "Preparing...\n";
            }

            $first = Property::first();
            if(!$first){
                echo "No existing data to update.\n\n";
                die();
            }
        }

        public function UpdateAll($log = false){

            $this->prepare($log);

            $test = new \App\Custom\APIFetcher();

            $sync = Sync::first();

            $update = [];

            for($z = 1 ; $z <= $sync->last_inserted_page ; $z++){

                if($log){
                    echo "Fetching data for page : ".$z."\n";
                }

                $test->setPageNum($z);

                $fetched = $test->fetchPage();

                if((int) $fetched->getStatusCode() != 200){

                    if($log){
                        echo "Failed to fetch data\n";
                    }

                    die();

                }

                $body = json_decode($fetched->getBody());

                if(empty($body->data)){
                    if($log){
                        echo "Failed to fetch data\n";
                    }

                    die();
                }

                $loop_to = (Config::get('app.per_page')-1);

                if($z == $sync->last_inserted_page){
                    $loop_to = $sync->last_inserted_element_index;
                }

                if($log){
                    echo "Preparing the data...\n";
                }

                for($x = 0 ; $x <= $loop_to ; $x++){

                    $last_updated_sync_seconds = \Datetime::createFromFormat('Y-m-d H:i:s', $sync->last_updation_datetime)->getTimeStamp();

                    $last_updated_entry_seconds = \Datetime::createFromFormat('Y-m-d H:i:s', $body->data[$x]->updated_at)->getTimeStamp();

                    $sale_or_rent_type = Config::get('app.rent');

                    if(trim(strtolower($body->data[$x]->type)) == 'sale'){
                        $sale_or_rent_type = Config::get('app.sale');
                    }

                    if($last_updated_entry_seconds > $last_updated_sync_seconds){

                        array_push($update, [
                            'uuid'                          =>      $body->data[$x]->uuid,
                            'county_name'                   =>      $body->data[$x]->county,
                            'country_name'                  =>      $body->data[$x]->country,
                            'town_name'                     =>      $body->data[$x]->town,
                            'description'                   =>      $body->data[$x]->description,
                            'address'                       =>      $body->data[$x]->address,
                            'image'                         =>      str_ireplace('lorempixel', 'loremflickr', $body->data[$x]->image_full),
                            'thumbnail'                     =>      str_ireplace('lorempixel', 'loremflickr', $body->data[$x]->image_thumbnail),
                            'latitude'                      =>      $body->data[$x]->latitude,
                            'longitude'                     =>      $body->data[$x]->longitude,
                            'number_of_bedrooms'            =>      $body->data[$x]->num_bedrooms,
                            'number_of_bathrooms'           =>      $body->data[$x]->num_bathrooms,
                            'price'                         =>      $body->data[$x]->price,
                            'property_type_id'              =>      $body->data[$x]->property_type_id,
                            'sale_or_rent_type'             =>      $sale_or_rent_type,
                            'updated_at'                    =>      now()
                        ]);

                    }


                }


            }


            if($log){
                echo "Updating...\n";
            }

            
            $update = array_chunk($update, 500);
            
            $this->updateQuery($update, $log);


        }

        public function updateQuery($update, $log = false){

            /*
            / updating 500 rows at once this way, could be increased at huge level by modifying mysql config for data packet.
            */

            /* using dynamic column names from table. */
            $fields = \DB::getSchemaBuilder()->getColumnListing('properties');
            unset($fields[0]);
            unset($fields[1]);
            unset($fields[16]);
            unset($fields[17]);
            $fields = array_values($fields);
            
            foreach($update as $chunk){

                $query = '';

                $params = [];
                $uuids = [];    

                $query = 'UPDATE `properties` SET ';
                
                foreach($fields as $field){

                    $query .= '`'.$field.'` = CASE `uuid` ';

                    for($c = 0 ; $c < count($chunk) ; $c++){

                        if(!$this->validateChars($chunk[$c]['uuid'])){
                            die("Invalid ID, Exited.");
                        }

                        $query .= 'WHEN \''.$chunk[$c]['uuid'].'\' THEN ? ';

                        $sale_rent = \Config::get('app.rent');

                        if($field == 'sale_or_rent_type'){

                            if(trim(strtolower($chunk[$c][$field])) == 'sale'){
                                $sale_rent = \Config::get('app.sale');
                            }

                            array_push($params, $sale_rent);

                        }else{

                            array_push($params, $chunk[$c][$field]);

                        }

                        array_push($uuids, "'".$chunk[$c]['uuid']."'");

                    }

                    
                    $query .= 'END,';

                }

                $query = rtrim($query, ",");

                $uuids = implode(',', $uuids);
                $query .= ' WHERE `uuid` in ('.$uuids.')';
    
                \DB::update($query, $params);

                if($log){
                    echo "Chunk Updated.\n";
                }

            }

            $sync = Sync::first();
            $sync->last_updation_datetime = now();
            $sync->save();

        }

        public function validateChars($uuid){

            $uuid = str_ireplace('-', '', $uuid);
            if(!preg_match('/[^A-Za-z0-9]/', $uuid)){
                return true;
            }

            return false;

        }

    }