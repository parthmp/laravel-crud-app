<template>
    <div>
        <form :action="action" method="POST" @submit="submitMe" enctype="multipart/form-data">
            
            <input type="hidden" name="_token" :value="csrf">
            <div>
                <p class="alert alert-danger" v-for="(e,i) in errors" :key="i">{{ e }}</p>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="county">County</label>
                        <input type="text" v-model="county" name="county" class="form-control" id="county">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" v-model="country" name="country" class="form-control" id="country">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="town">Town</label>
                        <input type="text" v-model="town" name="town" class="form-control" id="town">
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" v-model="description" cols="30" rows="5"></textarea>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" v-model="address" name="address" class="form-control" id="address">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bedroom">Number of Bedrooms</label>
                        <select name="bedroom" id="bedroom" class="form-control" v-model="bedroom">
                            <option value="">Select</option>
                            <option v-for="(br, i) in f_bedrooms" :key="i" :value="br.number_of_bedrooms">{{ br.number_of_bedrooms }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bathroom">Number of Bathrooms </label>
                        <select name="bathroom" id="bathroom" class="form-control" v-model="bathroom">
                            <option value="">Select</option>
                            <option v-for="(bath, i) in f_bathrooms" :key="i" :value="bath.number_of_bathrooms">{{ bath.number_of_bathrooms }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" min="1" step="0.01" v-model="price" name="price" class="form-control" id="price">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="property_type">Property Type</label>
                        <select name="property_type" id="property_type" class="form-control" v-model="property_type">
                            <option value="">Select</option>
                            <option v-for="(pt, i) in f_ptypes" :key="i" :value="pt.id">{{ pt.property_type }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sale_rent">Sale/Rent</label>
                        <br>
                        <label><input type="radio" name="sale_rent" value="1" v-model="sale_rent">&nbsp;Sale</label>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <label><input type="radio" name="sale_rent" value="0" v-model="sale_rent">&nbsp;Rent</label>
                    </div>
                </div>
                
            </div>
            <hr>
            <span v-if="image != '' && image != null"><img :src="image" class="w-100 rounded" alt=""></span>
            <br>
            <br>
            <input type="file" name="image">
            <hr>
            <button class="btn btn-primary float-right">Save</button>
            <div class="clearfix"></div>
        </form>
    </div>
</template>

<script>
    export default {

        props : ['action', 'values', 'bedrooms', 'bathrooms', 'ptypes', 'csrf'],

        data : function(){
            return {

                f_bedrooms : {},
                f_bathrooms : {},
                f_ptypes : {},
                errors : [],

                county : '',
                country : '',
                town : '',
                description : '',
                address : '',
                bedroom : '',
                bathroom : '',
                property_type : '',
                price : '',
                sale_rent : '',
                image : ''

            };
        },

        methods : {

            submitMe : function(e){

                this.errors = [];

                if(
                    this.county.trim() != '' &&
                    this.country.trim() != '' &&
                    this.town.trim() != '' &&
                    this.address.trim() != '' &&
                    this.bedroom != '' &&
                    this.bathroom != '' &&
                    this.property_type != '' &&
                    this.price != '' &&
                    this.sale_rent !== ''
                ){

                    return true;

                }else{

                    if(this.county.trim() == ''){
                        this.errors.push('Please add county');
                    }

                    if(this.country.trim() == ''){
                        this.errors.push('Please add country');
                    }

                    if(this.town.trim() == ''){
                        this.errors.push('Please add town');
                    }

                    if(this.address.trim() == ''){
                        this.errors.push('Please add address');
                    }

                    if(this.bedroom == ''){
                        this.errors.push('Please select number of bedrooms');
                    }

                    if(this.bathroom == ''){
                        this.errors.push('Please select number of bathrooms');
                    }

                    if(this.property_type == ''){
                        this.errors.push('Please select property type');
                    }

                    if(this.price == ''){
                        this.errors.push('Please enter price');
                    }
                    console.log(this.sale_rent);
                    if((this.sale_rent !== 0 && this.sale_rent !== 1) || this.sale_rent === ''){
                        this.errors.push('Please select sale or rent option');
                    }

                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    e.preventDefault();

                }

                
                
            }
        },

        created : function(){

            this.f_bedrooms = JSON.parse(this.bedrooms);
            this.f_bathrooms = JSON.parse(this.bathrooms);
            this.f_ptypes = JSON.parse(this.ptypes);
            
            let tmp = JSON.parse(this.values);

            if(typeof this.values !== 'undefined' && this.values != null && this.values != '' && tmp.length != 0){

                let all_values = JSON.parse(this.values);
                
                this.county = all_values.county_name;
                this.country = all_values.country_name;
                this.town = all_values.town_name;
                this.description = all_values.description;
                this.address = all_values.address;
                this.bedroom = all_values.number_of_bedrooms;
                this.bathroom = all_values.number_of_bathrooms;
                this.price = all_values.price;
                this.property_type = all_values.property_type_id;
                this.sale_rent = all_values.sale_or_rent_type;

                this.image = all_values.image;
                
            }

            


            
        }

        
    }
</script>
