<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">{{ ucfirst($notice->heading) }}</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 ">
            {!! $notice->description !!}
        </div>
    </div>
    @if($user->can('view_notice'))
        <hr>
        <div class="row">
            <div class="col-xs-12">
                <label class="font-12" for="">@lang('app.readBy')</label><br>
                <div class="message-center">
                    @forelse($readMembers as $member)
                        <img data-toggle="tooltip" data-original-title="@if($member->user->id == $user->id) @lang('app.me') @else {{ ucwords($member->user->name) }} @endif" src="{{ $member->user->image_url }}"
                             alt="user" class="img-circle" width="25" height="25">
                    @empty
                        @lang('messages.noUserFound')
                    @endforelse
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-12">
                <label class="font-12" for="">@lang('app.unReadBy')</label><br>
                <div class="message-center">
                    @forelse($unReadMembers as $member)
                        <img data-toggle="tooltip" data-original-title="@if($member->user->id == $user->id) @lang('app.me') @else {{ ucwords($member->user->name) }} @endif" src="{{ $member->user->image_url }}"
                             alt="user" class="img-circle" width="25" height="25">
                    @empty
                        @lang('messages.noUserFound')
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">@lang('app.close')</button>
</div>
