<!doctype html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">

    <title>Slim Examples</title>

    <link rel="stylesheet" href="css/slim.min.css">

    <style>
        /* center main column */
        html {
            font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        main {
            max-width:400px;
            margin:0 auto;
        }
    </style>

</head>
<body>

<main>

    <h1>&nbsp;</h1>

    <p>Accepts only jpegs and gifs.</p>

    <form action="sync.php" method="post" enctype="multipart/form-data">

        <div class="slim">
            <input type="file" accept="image/jpeg, image/gif"/>
        </div>
        <div class="slim">
            <input type="file2" accept="image/jpeg, image/gif"/>
        </div>
        <button type="submit">Upload</button>

    </form>
    
    
    

</main>

<script src="js/slim.kickstart.js"></script>

</body>
</html>