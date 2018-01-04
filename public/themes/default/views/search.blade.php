<!-- main-section -->
<!-- <div class="main-content"> -->
<style>
    .search-wrapper {
        min-height: 80vh;
    }
</style>
<div class="container container--standard">
    <div class="row">
        <div class="col-md-12">

            @if (Session::has('message'))
                <div class="alert alert-{{ Session::get('status') }}" role="alert">
                    {!! Session::get('message') !!}
                </div>
            @endif

            @if(isset($active_announcement))
                <div class="announcement alert alert-info">
                    <a href="javascript:;" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <h3>{{ $active_announcement->title }}</h3>
                    <p>{{ $active_announcement->description }}</p>
                </div>
            @endif
            <div class="search-wrapper" id="app-search">
                <app-search></app-search>
            </div>
        </div>
    </div>
</div>