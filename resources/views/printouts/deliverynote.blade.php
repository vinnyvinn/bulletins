<div style="margin-top: -50px;">
    @foreach(['Client\'s', 'Mombasa Office', 'Nairobi Account\'s'] as $copyHeader)
        <div style="clear: both; display: block; float: none;">

            <div style="display: block; float: none;">
                @include('printouts.deliverynote-raw')
            </div>
        </div>

        <div class="page-break"></div>
    @endforeach
</div>
