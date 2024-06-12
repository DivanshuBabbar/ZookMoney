<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="TxGEF4WSe6xsCaC60LtdO2e6pvAt0I058MQaUf1Q">
    <link rel="manifest" href="https://f6.hyvikk.space/web-manifest.json?v2">
    <title>{{general_setting('site_name')}} - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{general_setting('site_icon')}}" type="image/png">
    <link rel="stylesheet" href="{{asset('assets/admin/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/ionicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/fullcalendar.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/fullcalendar.print.css')}}" media="print">
    <link rel="stylesheet" href="{{asset('assets/admin/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/blue.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/morris.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/jquery-jvectormap-1.2.2.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap3-wysihtml5.min.css')}}">
    <link href="{{asset('assets/admin/css/fonts.css')}}" rel="stylesheet">
    <link href="{{asset('assets/admin/css/pnotify.custom.min.css')}}" media="all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" integrity="sha256-sWZjHQiY9fvheUAOoxrszw9Wphl3zqfVaz1kZKEvot8=" crossorigin="anonymous">
    <script type="text/javascript">
      window.Laravel = {"csrfToken":"TxGEF4WSe6xsCaC60LtdO2e6pvAt0I058MQaUf1Q","subscription_url":"https:\/\/f6.hyvikk.space\/assets\/push_notification\/push_subscription.php","serviceWorkerUrl":"https:\/\/f6.hyvikk.space\/serviceWorker.js"};
    </script>
    
    <style type="text/css">
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
            font-size: 0.6em;
            height: 35px !important;
        }
        .error{
          font-weight: 400 !important;
          color:red;
        }
        .input-group input{
          width: 65% !important;
        }

       .table > thead > tr:first-child > th
        {
          border: none;
        }

        .sidebar-dark-info {
            background-color: #00a49e;
        }

        .sidebar-dark-info .sidebar a {
            color: #fffeff;
        }

        .sidebar-dark-info .nav-treeview>.nav-item>.nav-link {
            color: #fffeff;
        }

    </style>

    @stack('styles')
  </head>
  <body class="hold-transition sidebar-mini">
  <input id="loggedinuser" name="loggedinuser" type="hidden" value="1">
  <input id="user_type" name="user_type" type="hidden" value="S">
    <div class="wrapper">
      <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
      <ul class="navbar-nav">
      <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
      </ul>
        <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
        
        </li>
          <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-user-circle"></i>
          <span class="badge badge-danger navbar-badge"></span>
          </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <a href="#" class="dropdown-item">
              <div class="media">
              
              <div class="media-body">
              <h3 class="dropdown-item-title">
              Super Administrator
              <span class="float-right text-sm text-danger">
              </span>
              </h3>
              
              <p class="text-sm text-muted"></p>
              </div>
              </div>
                <div>
                <div style="margin: 5px;">
                  <a href="{{general_setting('site_url')}}/en/home" class="btn btn-secondary btn-flat"><i class="fa fa-edit"></i> User Dashboard</a>
                  <form action="{{route('admin.logout')}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-flat pull-right" data-cf-modified-=""> 
                      <i class="fa fa-sign-out"></i>Logout
                    </button>
                  </form>
                </div>
                  <div class="clear"></div>
                </div>
              </a>
            </div>
          </li>
        </ul>
      </nav>
      @include('admin.partials.sidenav')
      @yield('content')
      <footer class="main-footer">
      <div class="float-left d-none d-sm-inline-block mb-3">
      <strong><p><span style="font-size: 16px;">© {{general_setting('site_name')}}  2023. All Rights Reserved.&nbsp;<span class="vertical-spacer d-none d-lg-inline">|</span>&nbsp;Powered By&nbsp;</span><a href="{{general_setting('site_url')}} " target="_blank" class="link"><span style="font-size: 16px;">{{general_setting('site_name')}} </span></a></p></strong>
      </div>
      <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 6.5
      </div>
      </footer>
  </div>
  <!-- <script data-cfasync="false" src="{{asset('assets/admin/newdash/js/email-decode.min.js')}}"></script> -->
  <script src="{{asset('assets/admin/newdash/js/jquery.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/admin/newdash/js/jquery-ui.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/admin/newdash/js/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('assets/admin/newdash/js/app.js')}}"></script>
  <script src="{{asset('assets/admin/newdash/js/select2.full.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/admin/newdash/js/icheck.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/admin/newdash/js/fastclick.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/admin/newdash/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/admin/newdash/js/dataTables.bootstrap4.min.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('assets/admin/newdash/js/dataTables.buttons.min.js')}}"></script>
  <script type="text/javascript" src="{{asset('assets/admin/newdash/js/buttons.print.min.js')}}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/1.5.1/js/buttons.flash.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/1.5.1/js/buttons.html5.js"></script>

  <script src="{{asset('assets/admin/newdash/js/adminlte.js')}}" type="text/javascript"></script>
  <script src="{{asset('assets/admin/newdash/js/web-sw.js')}}" type="text/javascript"></script>
  <script type="text/javascript" src="{{asset('assets/admin/newdash/js/pnotify.custom.min.js')}}"></script>
  <script type="text/javascript">
    var base_url = '/';
  </script>
  <script src="{{asset('assets/admin/newdash/js/rocket-loader.min.js')}}" data-cf-settings="c909c2c14ce1ed1a0fd24522-|49" defer=""></script>
  <script src="https://kit.fontawesome.com/b26330983c.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js" integrity="sha256-Y16qmk55km4bhE/z6etpTsUnfIHqh95qR4al28kAPEU=" crossorigin="anonymous"></script>
  <script>
    const Toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
  </script>
  @stack('scripts')
</body>
</html>