<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('modules.tasks.taskCategory')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('modules.taskCategory.categoryName')</th>
                    <th>@lang('modules.taskCategory.projectvisibility')</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $key=>$category)
                    <tr id="cat-{{ $category->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($category->category_name) }}</td>
                        <td>{{ ucwords($category->category_visibility) }}</td>
                        <td><a href="javascript:;" data-cat-id="{{ $category->id }}" class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noTaskCategory')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        {!! Form::open(['id'=>'createTaskCategoryForm','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label>@lang('app.add') @lang('modules.taskCategory.categoryName')</label>
                        <input type="text" name="category_name" id="category_name" class="form-control">
                    </div>
                </div>
                <div class="col-xs-6 ">
                    <div class="form-group">
                        <label>@lang('app.add') @lang('modules.taskCategory.projectvisibility')</label>
                        <select class="select2 form-control" name="category_visibility" id="category_visibility"
                                                data-style="form-control">
                                <option value="0">Normal</option>
                                <option value="1">Pilot</option>
                                <option value="2">High</option>
                                <option value="3">DSP</option>
                                <option value="4">Cindy Behrends Priority Sites</option>
                        </select>
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
    $('#createTaskCategoryForm').submit(function () {
        $.easyAjax({
            url: '{{route('admin.taskCategory.store')}}',
            container: '#createTaskCategoryForm',
            type: "POST",
            data: $('#createTaskCategoryForm').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
        return false;
    })

    $('.delete-category').click(function () {
        var id = $(this).data('cat-id');
        var url = "{{ route('admin.taskCategory.destroy',':id') }}";
        url = url.replace(':id', id);

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, '_method': 'DELETE'},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    $('#cat-'+id).fadeOut();
                }
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.taskCategory.store')}}',
            container: '#createTaskCategoryForm',
            type: "POST",
            data: $('#createTaskCategoryForm').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>