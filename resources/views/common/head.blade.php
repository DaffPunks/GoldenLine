<head>
	<title>{{$title}}</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf_token" content="{{ csrf_token() }}" />
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<link href='https://fonts.googleapis.com/css?family=Roboto&subset=latin,cyrillic-ext,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="/css/common.css">
	@yield('headExtra')
</head>