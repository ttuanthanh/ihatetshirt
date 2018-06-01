<div class="form-subscribe-wrap">
    <form class="form form--subscribe" id="form-subscribe" action="{{ route('admin.subscribers.add') }}" method="post">
        <div class="row">
            <label for="staticEmail" class="col-sm-12 col-md-6">Subscribe for special offers &amp; updates</label>
            <div class="col-sm-12 col-md-6">
				<div class="input-group">
				    <input class="form-control" type="email" name="email" placeholder="Enter your email here">
				    <button type="submit" class="btn btn-primary" type="button">subscribe</button>
				</div>
				<small class="msg-email"></small>
            </div>
        </div>            
    </form>
</div>