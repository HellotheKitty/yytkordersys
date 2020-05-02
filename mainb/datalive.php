<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<!doctype html>
<html>
<head>
    <title>Statistic Production</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link id="style" rel="stylesheet" type="text/css" href="../css/dashboard.css" />
    <script type="text/javascript" src="http://static.yikayin.com/js/jquery.min.js?nio1.0.2"></script>

</head>
<body>
<div class="loadingSection">Initializing...</div>

<div class="listWrapper">

</div>

<script type="text/javascript">

    function countdown(secs) {
        for(var i=secs;i>=0;i--) {
            window.setTimeout('pageRolad(' + i + ')', (secs-i) * 1000);
        }
    }
    function pageRolad(num) {
        $(".s-notice").html(num);
        if (num==0) {
            getData();
        }
    }

    function getData() {

        var w = $('.listWrapper');
        var l = $('.loadingSection');

        $.ajax({
            cache: false,
            type: "POST",
            url: "notify_prod.php?",
            data: {},
            dataType: 'json',
            beforeSend: function() {

                if (w.hasClass('initialized')) {

                    $(".s-notice").html("updating...");
                }
            },
            error: function(request) {

                countdown(15);
            },
            success: function(data) {

                if (data.reload == 1) {
                    <? if ((time() - $_GET['reloadTime'] > 300) || !isset($_GET['reloadTime'])) { ?>
                    window.location.href = '<? echo $_SERVER['REQUEST_URI'] ?>&reloadTime=<? echo time() ?>';
                    <? } ?>
                }

                if ( ! l.hasClass('hide')) l.addClass('hide');

                if (data.cssReload == 1) $('#style').attr('href', '../css/dashboard.css?' + Math.random());

                w.html(data.list).addClass('initialized');
                countdown(120);
            }
        })
    }

    $(document).ready(function() {

        getData();
    });

</script>

</body>
</html>