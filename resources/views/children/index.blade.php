<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>{{ $page_title }}</title>
      <meta name="_token" content="{{ csrf_token() }}" />
      <link rel="stylesheet" href="{{ asset('favicon.ico') }}">
      <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
      <script src="{{ asset('js/jquery.js') }}"></script>
      <script src="{{ asset('js/bootstrap.min.js') }}"></script>
      <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
      <script src="{{ asset("js/jquery-ui.js") }}"></script>
      <script src="{{ asset("js/common.js?v='.config('config.VERSION')") }}"></script>
      <script type="text/javascript">
         var SITE_URL = "{{ config('config.SITE_URL') }}";
         $(function() {
           $.ajaxSetup({
             headers: {
               'X-CSRF-Token': $('meta[name="_token"]').attr('content')
             }
           });
         });
      </script>
   </head>
   <body>
      <section style="background-color: #8fc4b7;">
         <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
               <div class="col-lg-8 col-xl-6" style="width: 80%">
                  <div class="card rounded-3">
                     <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">Children Register</h3>
                        <form class="px-md-2" id="children_frm">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="mb-4">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control checkspecialchar" name="name" maxlength="70"/>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-4">
                                    <label class="form-label">Date Of Birth</label>
                                    <input type="text" class="form-control datepicker" readonly name="date_of_birth"/>
                                 </div>
                              </div>
                              <div class="col-md-6 mb-4">
                                    @php
                                        $classes = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                                    @endphp
                                 <label class="form-label">Select Class</label>
                                 <select class="select form-control" name="class">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class }}" >{{ $class }}</option>
                                    @endforeach
                                 </select>
                              </div>
                              @if(count($countries) > 0)
                              <div class="col-md-6 mb-4">
                                 <label class="form-label">Select Country</label>
                                 <select class="select form-control" id="country_id" name="country_id">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                 </select>
                              </div>
                              @endif
                              <div class="col-md-6 mb-4">
                                 <label class="form-label">State</label>
                                 <select class="select form-control" id="state_id" name="state_id">
                                    <option value="">Select State</option>
                                 </select>
                              </div>
                              <div class="col-md-6 mb-4">
                                 <label class="form-label">City</label>
                                 <select class="select form-control" id="city_id" name="city_id">
                                    <option value="">Select City</option>
                                 </select>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-outline">
                                    <label class="form-label">Zipcode</label>
                                    <input type="text" class="form-control removedspace phone" name="zipcode" maxlength="7"/>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-outline">
                                    <label class="form-label">Address</label>
                                    <textarea cols="5" rows="3" class="form-control checkspecialchar" name="address" maxlength="300"></textarea>
                                 </div>
                              </div>
                           </div>
                           <br>
                           <div class="child-pickup-section">
                              <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Person Names</label>
                                    <input type="text" class="form-control checkspecialchar" name="person_name[]" maxlength="70"/>
                                 </div>
                                 <div class="col-md-4">
                                    <label class="form-label">Picked-Up detail</label>
                                    @php
                                        $relations = ['Father', 'Mother', 'Brother', 'Sister', 'Grandfather', 'Grandmother'];
                                    @endphp
                                    <select class="select form-control select-relation" id="select_relationship_0" name="relationship[]">
                                        @foreach ($relations as $relation)
                                            <option value="{{ $relation }}">{{ $relation }}</option>
                                        @endforeach
                                    </select>
                                 </div>
                                 <div class="col-md-4">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control removedspace phone" name="phone[]" maxlength="10">
                                 </div>
                              </div>
                           </div>
                           <div id="append_relation_template"></div>
                           <hr>
                           <a href="javascript:void(0);" id="add_more" class="btn btn-info btn-lg mb-1">Add More Picked-Up detail</a>
                           <hr>
                           <div class="mb-3">
                            <label class="form-label">Upload Child Photo</label>
                            <input class="form-control" type="file" id="photo" name="photo">
                          </div>
                          <p class="message" style="display: none;"></p>
                           <button type="submit" id="frm_btn" class="btn btn-success btn-lg mb-1">Submit</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      
      <section style="background-color: #8fc4b7;">
         <br>
         <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
               <div class="col-lg-8 col-xl-6" style="width: 100%">
                  <div class="card rounded-3">
                     <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">Childrens Data</h3>
                        <table class="table">
                           <thead>
                             <tr>
                               <th scope="col">#</th>
                               <th scope="col">Name</th>
                               <th scope="col">Date Of Birth</th>
                               <th scope="col">Class</th>
                               <th scope="col">Country</th>
                               <th scope="col">State</th>
                               <th scope="col">City</th>
                               <th scope="col">Zipcode</th>
                               <th scope="col">Address</th>
                               <th scope="col">Photo</th>
                               <th scope="col">Details</th>
                             </tr>
                           </thead>
                           <tbody id="tableData"></tbody>
                         </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>















   </body>
   <style>
    i.msg-error { color: red; }
    .error { color: red; }
    .success { color: rgb(0, 255, 42); }
   </style>
</html>