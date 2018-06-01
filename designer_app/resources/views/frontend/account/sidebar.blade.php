
<div class="img-thumbnail">
    <img src="{{ has_photo($info->profile_picture) }}" class="profile-pic">    
</div>


<div class="list-group">
    <a class="list-group-item list-group-item-action {{ actived(Request::segment(2), 'profile') }}" href="{{ route('frontend.account.profile') }}">My Profile</a>
    <a class="list-group-item list-group-item-action {{ actived(Request::segment(2), 'change-password') }}" href="{{ route('frontend.account.change-password') }}">Change Password</a>
    <a class="list-group-item list-group-item-action {{ actived(Request::segment(2), 'designs') }}" href="{{ route('frontend.account.designs') }}">My Designs</a>
    <a class="list-group-item list-group-item-action" href="{{ route('frontend.account.logout') }}">Log Out</a>
</div>

<style>
.img-thumbnail {
    margin-bottom: 20px;
}    
</style>