<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Track Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ asset('assets/admin/bootstrap/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/admin/dist/css/invoice.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

<div class="container">
  <div class="row">


    <h2>Track {{ e(Helpers::get_option('company_name')) }} invoice status</h2>

    @if(session('error'))
      <div class="alert alert-danger">
        <h3> {{ session('error')  }} </h3>
      </div>
    @endif


    <div class="col-md-offset-3 col-md-6">
      {!! Form::open(['class' => 'form-inline']) !!}
        <div class="form-group">
          <label for="invoice_id">Invoice ID</label>
          <input type="text" class="form-control" id="invoice_id" name="invoice_id" placeholder="Invoice ID">
        </div>
        <button type="submit" class="btn btn-primary">Track</button>
      </form>

    </div>
  </div>
</div>




</body>
</html>
