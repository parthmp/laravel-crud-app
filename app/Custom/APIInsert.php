<?php

    namespace App\Custom;

    use \GuzzleHttp\Client;
    use \Illuminate\Support\Facades\Config;
    use \App\Models\Sync;
    use \App\Models\PropertyType;
    use \App\Models\Property;

    class APIInsert{

        private function syncSet(){

            /* check if sync has data or not. */
            $sync = Sync::first();

            if(!$sync){
                $new_sync = new Sync();
                $new_sync->last_inserted_page = 0;
                $new_sync->last_inserted_element_index = (Config::get('app.per_page')-1);
                $new_sync->last_updation_datetime = "1970-01-01 08:00:00";
                $new_sync->save();
            }

        }

        public function InsertAll($log = false){

            if($log){
                echo "Preparing...\n";
            }

            $this->syncSet();

            $sync = Sync::first();

            $update_date_flag = false;

            if($sync->last_inserted_element_index < (Config::get('app.per_page')-1)){
                $page_num = $sync->last_inserted_page;
            }else{
                $page_num = ($sync->last_inserted_page+1);
            }

            if($page_num == 1){
                $update_date_flag = true;
            }
            
            $ele_index = 0;

            if($sync->last_inserted_element_index > 0 && $sync->last_inserted_element_index < (Config::get('app.per_page')-1)){
                $ele_index = ($sync->last_inserted_element_index+1);
            }

            if($log){
                echo "Sync Prepared\n";
            }

            $test = new \App\Custom\APIFetcher();

            $looper = true;

            $insert_property_types = [];

            $last_index_inserted = 0;
            
            /* add data by page */
            while($looper){

                if($log){
                    echo "Fetching data for page : ".$page_num."\n";
                }

                $insert_properties = [];

                $test->setPageNum($page_num);
                $fetched = $test->fetchPage();

                if((int) $fetched->getStatusCode() != 200){

                    if($log){
                        echo "Failed to fetch data\n";
                    }

                    die();

                }

                $body = json_decode($fetched->getBody());

                if(empty($body->data)){

                    $looper = false;

                }else{

                    $skip_flag = 1;

                    if($log){
                        echo "Inserting data for page : ".$page_num."\n";
                    }
                    
                    /* insert properties */

                    $all_data = $body->data;
                    
                    for($z = $ele_index ; $z < count($all_data) ; $z++){

                        $sale_or_rent_type = Config::get('app.rent');

                        if(trim(strtolower($all_data[$z]->type)) == 'sale'){
                            $sale_or_rent_type = Config::get('app.sale');;
                        }

                        array_push($insert_properties, [
                            'uuid'                  =>      $all_data[$z]->uuid,
                            'county_name'           =>      $all_data[$z]->county,
                            'country_name'          =>      $all_data[$z]->country,
                            'town_name'             =>      $all_data[$z]->town,
                            'description'           =>      $all_data[$z]->description,
                            'address'               =>      $all_data[$z]->address,
                            'image'                 =>      str_ireplace('lorempixel', 'loremflickr', $all_data[$z]->image_full),
                            'thumbnail'             =>      str_ireplace('lorempixel', 'loremflickr', $all_data[$z]->image_thumbnail),
                            'latitude'              =>      $all_data[$z]->latitude,
                            'longitude'             =>      $all_data[$z]->longitude,
                            'number_of_bedrooms'    =>      $all_data[$z]->num_bedrooms,
                            'number_of_bathrooms'   =>      $all_data[$z]->num_bathrooms,
                            'price'                 =>      $all_data[$z]->price,
                            'property_type_id'      =>      $all_data[$z]->property_type_id,
                            'sale_or_rent_type'     =>      $sale_or_rent_type,
                            'created_at'            =>      now(),
                            'updated_at'            =>      now()
                        ]);
                        
                        array_push($insert_property_types, [
                            'id'                    =>      $all_data[$z]->property_type->id,
                            'property_type'         =>      $all_data[$z]->property_type->title,
                            'description'           =>      $all_data[$z]->property_type->description,
                            'created_at'            =>      now(),
                            'updated_at'            =>      now()
                        ]);

                        $last_index_inserted = $z;
                        $skip_flag = 0;

                    }

                    if($last_index_inserted == 0){
                        $last_index_inserted = $sync->last_inserted_element_index;
                    }

                    Property::insert($insert_properties);
                    $sync->last_inserted_page = $page_num;
                    $sync->last_inserted_element_index = $last_index_inserted;
                    if($update_date_flag){
                        $sync->last_updation_datetime = now();
                    }
                    $sync->save();

                    if($log){
                        if($skip_flag == 0){
                            echo "Inserted data for page : ".$page_num."\n\n\n";
                        }else{
                            echo "Skipping data for page : ".$page_num."\n\n\n";
                        }
                        
                    }

                    $insert_property_types = $this->filterPropertyTypes($insert_property_types);

                }

                $page_num++;

            }

            if($log){
                echo "Property types updated.\n";
            }

            PropertyType::upsert($insert_property_types, ['id'], ['property_type', 'description']);

        }

        public function filterPropertyTypes($types){

            $ids = [];
            $filtered = [];

            for($z = 0 ; $z < count($types) ; $z++){
                if(!in_array($types[$z]['id'], $ids)){
                    array_push($ids, $types[$z]['id']);
                    array_push($filtered, $types[$z]);
                }
            }

            return $filtered;

        }

    }