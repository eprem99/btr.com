<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@lang('app.sporttype')</h4>
</div>
<div class="modal-body">
    <div class="portlet-body">
        {!! Form::open(['id'=>'createSporttype','class'=>'ajax-form','method'=>'POST']) !!}
        <div class="form-body">
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label class="required">@lang('app.name')</label>
                        <input type="text" name="sporttype_name" id="sporttype_name" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="button" id="save-sporttype" onclick="saveSporttype(); return false;" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
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
    function saveSporttype() {
        var sporttypeName = $('#sporttype_name').val();
        var token = "{{ csrf_token() }}";
        $.easyAjax({
            url: '{{route('admin.sporttype.quick-store')}}',
            container: '#createSporttype',
            type: "POST",
            data: { 'name':sporttypeName, '_token':token},
            success: function (response) {
                if(response.status == 'success'){
                    if ($('#sporttype').length !== 0) {
                        $('#sporttype').html(response.teamData);
                        $("#sporttype").select2();
                        $('#sporttypeModel').modal('hide');                        
                    } else {
                        window.location.reload();
                    }
                }
            }
        })
        return false;
    }
</script>