<!doctype html>
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
  <meta name="color-scheme" content="light dark" />
  <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
  <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
  <meta name="title" content="AdminLTE v4 | Dashboard" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description" content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."/>
  <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"/>
  <meta name="supported-color-schemes" content="light dark" />
  <link rel="preload" href="{{ asset('adminlte/css/adminlte.css') }}" as="style" />
  <!--Start CSS-->
  @include('layouts.css')
  <!--End CSS-->
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
  <div class="app-wrapper">
    <!--Begin Navbar-->
    @include('layouts.topbar')  
    <!--End Navbar-->
    <!--Begin Sidebar-->
    @include('layouts.sidebar')
    <!--End Sidebar-->
    <!--Begin Main-->
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <!--Begin Content-->
          @yield('content')
          <!--End Content-->
        </div>
      </div>
    </main>
    <!--Begin Footer-->
    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <strong>
        Copyright &copy; 2014-2025&nbsp;
        <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
      </strong>
        All rights reserved.
    </footer>
    <!--End Footer-->
  </div>
<!--Begin Scripts-->
@include('layouts.scripts')
<!--End Scripts--> 
</body>
</html>

  