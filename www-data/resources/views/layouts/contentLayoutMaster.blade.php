<!DOCTYPE html>
<!--
Template Name: Frest HTML Admin Template
Author: :Pixinvent
Website: http://www.pixinvent.com/
Contact: hello@pixinvent.com
Follow: www.twitter.com/pixinvents
Like: www.facebook.com/pixinvents
Purchase: https://1.envato.market/pixinvent_portfolio
Renew Support: https://1.envato.market/pixinvent_portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.

-->
{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
// confiData variable layoutClasses array in Helper.php file.
  $configData = Helper::applClasses();
@endphp

<html class="loading" lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif"
 data-textdirection="{{$configData['direction'] == 'rtl' ? 'rtl' : 'ltr' }}">
  <!-- BEGIN: Head-->

    <head>
    <meta  charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') Montekristmedia</title>
    <link rel="apple-touch-icon" href="{{asset('images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/ico/favicon.ico')}}">

    {{-- Include core + vendor Styles --}}
    @include('panels.styles')
    </head>
    <!-- END: Head-->

     @if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
     @include(($configData['mainLayoutType'] === 'horizontal-menu') ? 'layouts.horizontalLayoutMaster':'layouts.verticalLayoutMaster')
     @else
     {{-- if mainLaoutType is empty or not set then its print below line --}}
     <h1>{{'mainLayoutType Option is empty in config custom.php file.'}}</h1>
     @endif
      <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/tables/datatable/datatables.min.css')}}">

      <script src="{{asset('vendors/js/tables/datatable/datatables.min.js')}}"></script>
      <script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap4.min.js')}}"></script>
      <script src="{{asset('vendors/js/tables/datatable/dataTables.buttons.min.js')}}"></script>
      <script src="{{asset('vendors/js/tables/datatable/buttons.html5.min.js')}}"></script>
      <script src="{{asset('vendors/js/tables/datatable/buttons.print.min.js')}}"></script>
      <script src="{{asset('vendors/js/tables/datatable/buttons.bootstrap.min.js')}}"></script>
      <script src="{{asset('vendors/js/tables/datatable/pdfmake.min.js')}}"></script>
      <script src="{{asset('vendors/js/tables/datatable/vfs_fonts.js')}}"></script>
      <script src="{{asset('vendors/js/moment.js')}}"></script>
      <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/pickadate/pickadate.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/pickers/daterange/daterangepicker.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/extensions/sweetalert2.min.css')}}">
      <script src="{{asset('vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
      <script src="{{asset('vendors/js/pickers/pickadate/picker.js')}}"></script>
      <script src="{{asset('vendors/js/pickers/pickadate/picker.date.js')}}"></script>
      <script src="{{asset('vendors/js/pickers/pickadate/picker.time.js')}}"></script>
      <script src="{{asset('vendors/js/pickers/pickadate/legacy.js')}}"></script>
      <script src="{{asset('vendors/js/pickers/daterange/daterangepicker.js')}}"></script>
      <link rel="stylesheet" type="text/css" href="{{asset('vendors/css/forms/select/select2.min.css')}}">

  {{--      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



  {{--     <script src="{{ asset('js/DataTables/datatables.js') }}"></script>--}}
      @yield('footer-admin')
  <style>
      div.dataTables_wrapper div.dataTables_length select {
          width: 70px;
      }
      .select2-container--classic .select2-selection--single .select2-selection__arrow b, .select2-container--default .select2-selection--single .select2-selection__arrow b {
          transform: rotate(0deg);
      }
      .select2.select2-container {
          width: 100%!important;
      }
  </style>
  <script>

      $('.select2').select2();

      function fallbackCopyTextToClipboard(text) {
          var textArea = document.createElement("textarea");
          textArea.value = text;

          // Avoid scrolling to bottom
          textArea.style.top = "0";
          textArea.style.left = "0";
          textArea.style.position = "fixed";

          document.body.appendChild(textArea);
          textArea.focus();
          textArea.select();

          try {
              var successful = document.execCommand('copy');
              var msg = successful ? 'successful' : 'unsuccessful';
              console.log('Fallback: Copying text command was ' + msg);
          } catch (err) {
              console.error('Fallback: Oops, unable to copy', err);
          }

          document.body.removeChild(textArea);
      }
      function copyTextToClipboard(text) {
          if (!navigator.clipboard) {
              fallbackCopyTextToClipboard(text);
              return;
          }
          navigator.clipboard.writeText(text).then(function() {
              console.log('Async: Copying to clipboard was successful!');
          }, function(err) {
              console.error('Async: Could not copy text: ', err);
          });
      }

      function copy(path) {
          var copyTextarea = document.querySelector(path);
          copyTextToClipboard(copyTextarea.value)
      }

      @if(session()->has('message'))
      Swal.fire({
          position: 'center',
          type: 'success',
          title: '{{session('message')}}',
          showConfirmButton: false,
          timer: 1500
      })
      @endif
  </script>

</html>
