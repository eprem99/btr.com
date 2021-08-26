<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.clients.clientCategory')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.projectCategory.categoryName')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $key=>$category)
                    <tr id="cat-{{ $category->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($category->category_name) }}</td>
                        <td>
                            <a href="javascript:;" data-cat-id="{{ $category->id }}" class="btn btn-sm btn-success btn-rounded edit-category"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            <a href="javascript:;" data-cat-id="{{ $category->id }}" class="btn btn-sm btn-danger btn-rounded delete-category"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noProjectCategory')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        {!! Form::open(['id'=>'createClientCategory','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="required">@lang('modules.projectCategory.categoryName')</label>
                        <input type="text" name="category_name" id="category_name" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category_email" class="required">@lang('modules.client.categoryemail')</label>
                        <input type="text" name="category_email" id="category_email" class="form-control" value="">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category_phone" class="required">@lang('modules.client.categoryphone')</label>
                        <input type="text" name="category_phone" id="category_phone" class="form-control" value="">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="country" class="required">@lang('modules.stripeCustomerAddress.country')</label>
                        <select name="category_country" class="form-control" id="country">
                            <option value>@lang('app.site.country')</option>
                            @foreach ($countries as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label for="category_address" class="required">@lang('modules.client.categoryAddress')</label>
                        <input type="text" name="category_address" id="category_address" class="form-control" value="">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-category" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>

<script>
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.clientCategory.destroy',':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
              data: {'_token': token, '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
                    $('#cat-'+id).fadeOut();
                    var options = [];
                    var rData = [];
                    rData = response.data;                   
                    $.each(rData, function( index, value ) {
                        var selectData = '';
                        selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                        options.push(selectData);
                    });
                    $('#category_id').html(options);
                }
            }
        });
    });
    $('.edit-category').click(function () {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.clientCategory.edit',':id') }}";
        url = url.replace(':id', id);

        window.location.href = url;

    });
    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.clientCategory.store')}}',
            container: '#createClientCategory',
            type: "POST",
            data: $('#createClientCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    if(response.status == 'success'){
                        console.log(response.data);
                        var options = [];
                        var rData = [];
                        rData = response.data;
                        $.each(rData, function( index, value ) {
                            var selectData = '';
                            selectData = '<option value="'+value.id+'">'+value.category_name+'</option>';
                            options.push(selectData);
                        });

                        $('#category_id').html(options);
                        // $('#category_id').selectpicker('refresh');
                        $('#clientCategoryModal').modal('hide');
                    }
                }
            }
        })
    });
</script>