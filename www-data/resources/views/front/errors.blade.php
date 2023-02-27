<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Applications errors 🚀</title>
</head>
<body>
<h1 style="padding: 20px">Applications errors</h1>
<style>
    body {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.5;
        text-align: left;
        color: #3c4b64;
        background-color: #ebedef;
    }

    .custom-select {
        width: 50%;
    }


</style>





<div style="    padding: 20px;">
    <div class="">
        <a href="https://monte-media.com/show_errors?show_hide=1">Show hide records</a>
        <div style="width: 95%;padding: 20px;    background-color: #fff;">
        <table id="dt-filter-select" class="table table-hover table-striped" cellspacing="0">
            <thead>
            <tr>
                <th class="th-sm">#
                </th>
                <th class="th-sm">App
                </th>
                <th class="th-sm">Count
                </th>
                <th class="th-sm">Type
                </th>
                <th class="th-sm">Message
                </th>
                <th class="th-sm">URL
                </th>
                <th class="th-sm" style="width: 158px;">Date
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($errors as $error)
                <tr>
                    <td>{{ $error->id }}
                        <form action="#" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="hide_error_id" value="{{ $error->id }}">
                            <button type="submit">hide</button>
                        </form>
                    </td>
                    <td>{{ $error->app_id }}</td>
                    <td>{{ $error->count_error }}</td>
                    <td>{{ $error->type_error }}</td>
                    <td>
                        <a style="       color:inherit;" href="{{ $error->url }}" target="_blank">{{ Illuminate\Support\Str::limit($error->message_error, 270) }}</a>
                    </td>
                    <td>
                        <a href="{{ $error->url }}" target="_blank">{{ Illuminate\Support\Str::limit($error->url, 40) }}</a>
                        </td>

                    <td>{{ \App\Facades\Time::applyTimezone($error->created_at) }}</td>

                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th class="th-sm">#
                </th>
                <th class="th-sm">App
                </th>
                <th class="th-sm">Count
                </th>
                <th class="th-sm">Type
                </th>
                <th class="th-sm">Message
                </th>
                <th class="th-sm">URL
                </th>
                <th class="th-sm" style="width: 158px;">Date
                </th>
            </tr>
            </tfoot>
        </table>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.22/af-2.3.5/b-1.6.5/b-html5-1.6.5/r-2.2.6/sb-1.0.0/datatables.min.css"/>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.22/af-2.3.5/b-1.6.5/b-html5-1.6.5/r-2.2.6/sb-1.0.0/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap.min.js"></script>


{{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css"/>--}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css"/>



<script>



    $(document).ready(function () {
        $('#dt-filter-select').dataTable({
            "pageLength":25,


            initComplete: function () {
                this.api().columns().every( function () {
                    var column = this;
                    cn = column[0][0]
                    cn = parseInt(cn);
                    if (cn == 1 || cn == 3) {



                    var select = $('<select  class="browser-default custom-select form-control-sm"><option value="" selected>Search</option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });

                    column.data().unique().sort().each(function (d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });

                }

                } );
            },

            responsive: {
                details: {
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
                        tableClass: 'table'
                    } )
                }
            },

        });
    });
</script>

</body>
</html>
<script>
    import Form from "../../assets/js/admin/pages/views/client/partials/Form";
    export default {
        components: {Form}
    }
</script>
