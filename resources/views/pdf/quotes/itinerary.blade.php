@php
    /**
     * @var \App\Repository\Storage\Itinerary\Itinerary $itinerary
     * @var string $type
     */
    $type = $type ?? "Travel Itinerary"
@endphp

        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet" />

    <style type="text/css">
        <?php include(public_path() . '/css/kpt.css') ?>
        {!! setting('customization.documentation.colors') !!}
    </style>

    <style>

     

    </style>

    <title>{{ $itinerary->package }} | {{ $itinerary->reference }} | {{ $type }}</title>
</head>

<body class="body">

<main>
  <section>
    <div class="row">
      <div class="header">
         <div class="header-logo">
             <img src="" alt="logo">
         </div>
      </div>
  </section>
    

</main>


</body>
</html>
