    {{-- <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
    
    <script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/mdb.js') }}"></script> 
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script> --}}
    <script type="text/javascript" src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') . '?' . date('U')  }}"></script>
    {{-- these were placed in the header.blade.php - but should be here --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.min.js" integrity="sha512-uO6vGk8coV9uDaoMwYUTVO2nQ3XS4MVePe6qVif3PkiYRZ2y+707M4HOdaYPF0jqxhgNenarJ/1RlfRTs37SeA==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js" integrity="sha512-vv3EN6dNaQeEWDcxrKPFYSFba/kgm//IUnvLPMPadaUf5+ylZyx4cKxuc4HdBf0PPAlM7560DV63ZcolRJFPqA==" crossorigin="anonymous"></script>
</body>
</html>