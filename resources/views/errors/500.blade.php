<!DOCTYPE html>
<html lang="en">

<head>
    @include('errors.partials.head')
    @section('title', '500 Repair Booking')

</head>

<body>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="page-error">
                    <div class="page-inner">
                        <h1>500</h1>
                        <div class="page-description">
                            Whoopps, something went wrong.
                        </div>
                      
                    </div>
                </div>
                @include('errors.partials.foot')

            </div>
        </section>
    </div>
    @include('errors.partials.foot')

</body>

</html>
