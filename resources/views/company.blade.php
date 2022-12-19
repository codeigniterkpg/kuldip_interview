<html lang="en">
<head>
  <title>Company CRUD Operation</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="container">
    <div class="row">
      <div class="col-md-12">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Companies</h3></div>
            <div class="card-body">
                <button class="btn btn-primary" id="new_company">Create new company</button>
                <div class="card">
                  <div class="card-header"><h3 class="card-title">Company List</h3></div>
                  <div class="card-body">
                      <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Image</th>
                                    <th>Website</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @if(isset($companies) && !empty($companies))
                                        @foreach($companies as $company)
                                            <tr>
                                              <td>{{ $company->name }}</td>
                                              <td>{{ $company->address }}</td>
                                              <td>{{ $company->image }}</td>
                                              <td>{{ $company->website }}</td>
                                              <td>{{ $company->email }}</td>
                                              <td>
                                                  <button type="button" class="btn btn-primary edit" data-url="{{ route('api.company.destroy', $company->id) }}">Edit</button>
                                                  <button type="button" class="btn btn-danger delete" data-url="{{ route('api.company.destroy', $company->id) }}">Delete</button>
                                              </td>
                                            </tr>
                                        @endforeach
                                    @else
                                      <tr>
                                        <td colspan="6">Company could not found.</td>
                                      </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
          </div>
      </div>
    </div>
</div>

<!-- Modal START-->
<div class="modal" id="company-form-modal">
  <div class="modal-dialog">
    <form method="post" action="{{ route('api.company.store') }}" id="company-form">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Create Company</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">

          <div class="form-group" id="response">
            
          </div>

          <input type="hidden" name="_method" value="put" id="_method" disabled>

          <div class="form-group">
            <label for="email">Name:</label>
            <input type="text" class="form-control" placeholder="Enter name" name="name" id="name">
          </div>          
        
          <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control-file" name="image" id="image">
          </div>

          <div class="form-group">
            <label for="address">Address:</label>
            <textarea class="form-control" name="address" id="address"></textarea>
          </div>          

          <div class="form-group">
            <label for="website">Website:</label>
            <input type="text" class="form-control" placeholder="Enter website url" name="website" id="website">
          </div>

          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" placeholder="Enter email" name="email" id="email">
          </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>

      </div>
    </form>
  </div>
</div>
<!-- Modal END-->

<script type="text/javascript">
    $(document).ready(function() {
        var _modal = $("#company-form-modal");
        $(document).on("click", "#new_company", function() {
            _modal.modal("show");
        });

        $(document).on("click", ".delete", function() {
            var _this = $(this);
            if (confirm("Are you sure ?")) {
               $.ajax({
                  url: _this.attr("data-url"),
                  method: 'DELETE',
                  dataType: 'JSON',
                  success:function(response)
                  {
                      if (response.status) {
                          _this.closest(".row").remove();     
                          response.message && alert(response.message);
                          window.location.reload();
                      }
                  }
              }); 
            }
        });

        $(document).on("click", ".edit", function() {
            var _this = $(this), company_form = $("#company-form");
            $.ajax({
                  url: _this.attr("data-url"),
                  method: 'GET',
                  dataType: 'JSON',
                  success:function(response)
                  {
                      if (response.status) {
                          company_form.find("#name").val(response.data.name);
                          company_form.find("#address").val(response.data.address);
                          company_form.find("#website").val(response.data.website);
                          company_form.find("#email").val(response.data.email);

                          company_form.attr("action", _this.attr("data-url"));
                          company_form.find("#_method").prop("disabled", false);
                          _modal.modal("show");
                      }
                  }
              });
        });

        $(document).on("submit", "#company-form", function(event) {
            event.preventDefault();
            var url = $(this).attr('action');
            $("#response").html("");
            $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    if (response.status) {
                        response.message && $("#response").html("<div class='text-sccess'>" + response.message + "</div>");
                        window.location.reload();
                    }

                    if (response.errors) {
                        var $error_sting = '';
                        $.each(response.errors, function($index, $error){
                            $error_sting += '<p class="text-danger">'+$error+'</p>';
                        });
                        $("#response").html("<div class='alert alert-sccess'>" + $error_sting + "</div>");
                    }
                }
            });

        });   
    });
</script>

</body>
</html>