@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit Property</div>
                <div class="card-body">

                    <form action="" method="POST" onsubmit="return false">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="county">County</label>
                                    <input type="text" name="county" class="form-control" id="county">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <input type="text" name="country" class="form-control" id="country">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="town">Town</label>
                                    <input type="text" name="town" class="form-control" id="town">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary float-right">Save</button>
                        <div class="clearfix"></div>
                    </form>

                    

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
