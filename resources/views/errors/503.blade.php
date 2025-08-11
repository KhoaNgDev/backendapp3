<!DOCTYPE html>
<html lang="en">

<head>
    @include('errors.partials.head')
    @section('title', '503 Repair Booking')

</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="page-error">
                    <div class="page-inner">
                        <h1>503</h1>
                        <div class="page-description">Be right back.</div>
                      
                    </div>
                </div>
                @include('errors.partials.copyright')
            </div>
        </section>
    </div>

    @include('errors.partials.foot')

</body>

</html>
