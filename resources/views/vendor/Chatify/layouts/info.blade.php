{{-- user info and avatar --}}
<div class="avatar av-l chatify-d-flex"></div>
<p class="info-name">{{ config('chatify.name') }}</p>
@if (auth()->user()->type != 'Pasien')
    <div class="messenger-infoView-btns">
        <a href="#" class="danger delete-conversation">Delete Conversation</a>
    </div>
@endif
{{-- shared photos --}}
<div class="messenger-infoView-shared">
    <p class="messenger-title"><span>Shared Photos</span></p>
    <div class="shared-photos-list"></div>
</div>
