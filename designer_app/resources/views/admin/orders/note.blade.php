<div class="margin-bottom-10 n-c">
    <div class="note-content">{{ $note->post_content }}</div>
    <span class="help-inline">added on <b>{{ date('F d, Y', strtotime($info->created_at)) }}</b> at 
        <b>{{ date('H:i', strtotime($info->created_at)) }}</b> by <b>{{ $note->authorName }}</b> 
        <a href="#" data-id="{{ $note->id }}" class="delete-note pull-right btn-xs margin-top-10"><i class="fa fa-remove"></i></a></span>    
</div>
