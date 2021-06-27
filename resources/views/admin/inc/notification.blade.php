<div style="position: fixed; top: 20px; right: 11px; z-index: 99999;">
     @if($errors->any())
         <div class="alert alert-danger alert-sty alert-dismissible 1" role="alert">
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">×</span>
             </button>
             <ul class="list-unstyled my-0 py-0">
                 @foreach ($errors->all() as $error)
                     <li>{{ $error }}</li>
                 @endforeach
             </ul>
         </div>
     @elseif (session()->has('msg'))
         @if(session()->get('status'))
             <div class="alert alert-success alert-sty alert-dismissible 2" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">×</span>
                 </button>
                 <ul class="list-unstyled my-0 py-0">
                     <li>{{ session()->get('msg') }}</li>
                 </ul>
             </div>
         @else
             <div class="alert alert-danger alert-sty alert-dismissible 3" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">×</span>
                 </button>
                 <ul class="list-unstyled my-0 py-0">
                     <li>{{ session()->get('msg') }}</li>
                 </ul>
             </div>
         @endif
     @endif
</div>
