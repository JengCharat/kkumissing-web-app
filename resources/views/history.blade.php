<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<button onclick="openIndexPage()">Go to index page</button>


<script>
    function openIndexPage() {
        window.location.href = '/';  // เปลี่ยนเส้นทางไปยังหน้า /history
    }
</script>
    <form action="/history" method="post">
        @csrf
        <input type="text" name = "telNum" placeholder="input tel number">
        <button>submit</button>
    </form>
    @foreach ($tenant as $item)
       <h1>tenant name: {{$item->tenantName}}</h1>
       <h1>tenant type:{{$item->tenant_type}}  </h1>
       @if ($item->tenant_type == 'daily')
           <h1>check in {{$item->check_in}}</h1>
           <h1>check out {{$item->check_out}}</h1>

       @endif
        <h1>--------------------------------</h1>
    @endforeach
</body>
</html>
