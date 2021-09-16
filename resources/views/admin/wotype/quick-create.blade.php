<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.wotype')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createwotype','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('app.name')</label>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="required">@lang('app.price')</label>
                        <input type="text" name="price" id="price" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-wotype" onclick="savewotype(); return false;" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
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
    function savewotype() {
        var wotypetName = $('#name').val();
        var wotypetPrice = $('#price').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('admin.wotype.quick-store')}}',
            container: '#createwotype',
            type: "POST",
            data: { 'name':wotypetName, 'price':wotypetPrice, '_token':token},
            success: function (response) {
                if(response.status == 'success'){
                    if ($('#wotype').length !== 0) {
                        $('#wotype').html(response.teamData);
                        $("#wotype").select2();
                        $('#departmentModel').modal('hide');                        
                    } else {
                        window.location.reload();
                    }
                }
            }
        })
        return false;
    }
</script>