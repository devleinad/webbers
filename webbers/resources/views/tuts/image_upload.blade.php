<!doctype>
<html>
<head>
    <title>
        Image Upload
    </title>
</head>
<body>
    <form method="POST" action="{{url('/tuts')}}">
        @csrf
        <input type="file" name="image" accept="image/*">
        <button type="submit">Upload</button>
    </form>
</body>
</html>
