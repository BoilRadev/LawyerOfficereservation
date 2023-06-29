{#<!DOCTYPE html>#}
{#<html>#}
{#<head>#}
{#    <meta charset="utf-8">#}
{#    <meta http-equiv="X-UA-Compatible" content="IE=edge">#}
{#    <meta name="viewport" content="width=device-width, initial-scale=1">#}
{#    <!-- The above 3 meta tags must come first in the head; any other head content must come after these tags -->#}
{#    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">#}
{#    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->url->get('img/favicon.ico')?>"/>#}
{#</head>#}
{#<body>#}
{#    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->#}
{#    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>#}
{#    <!-- Latest compiled and minified JavaScript -->#}
{#    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>#}
{#    <nav class="navbar navbar-inverse">#}
{#        <ul class="nav navbar-nav">#}
{#            <li class="active"><a href="{{ url('index')}}">Home</a></li>#}
{#            {% if not session.has('auth') %}#}
{#                <li><a href="{{ url('registerAs/registerAs')}}">Register</a></li>#}
{#                <li><a href="{{ url('loginAs/loginAs')}}">Log In</a></li>#}
{#            {% else %}#}
{#                <li style="margin-left: 1250px;"><a href="<?= $this->url->get(['for' => 'clients-logout']) ?>">Log Out</a></li>#}
{#            {% endif %}#}
{#        </ul>#}
{#    </nav>#}

{#<div class="container">#}
{#    <?php echo $this->getContent(); ?>#}
{#</div>#}

{#</body>#}
{#</html>#}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags must come first in the head; any other head content must come after these tags -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->url->get('img/favicon.ico')?>"/>
</head>
<body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<nav class="navbar navbar-inverse">
    <ul class="nav navbar-nav">
        <li class="active"><a href="{{ url('index')}}">Home</a></li>
        {% if not session.has('auth') %}
            <li><a href="{{ url('registerAs/registerAs')}}">Register</a></li>
            <li><a href="{{ url('loginAs/loginAs')}}">Log In</a></li>
        {% else %}
            {% if session.get('auth')['role'] == 'lawyer' %}
                <li><a href="{{ url('appointments/search')}}">Action</a></li>
                <li><a href="{{ url('appointments/displayCalendar')}}">Calendar</a></li>
            {% endif %}
            <li style="margin-left: 1200px;"><a href="<?= $this->url->get(['for' => 'clients-logout']) ?>">Log Out</a></li>
        {% endif %}
    </ul>
</nav>

<div class="container">
    <?php echo $this->getContent(); ?>
</div>

</body>
</html>
