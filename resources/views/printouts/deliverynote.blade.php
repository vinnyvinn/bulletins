@foreach(['Customer\'s', 'Mombasa Office', 'Nairobi Account\'s'] as $copyHeader)
    @include('printouts.deliverynote-raw')
    <div class="page-break"></div>
@endforeach
