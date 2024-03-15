<header class="app_header">
   <h1 id="header-title" class="header_title">CloudCatcher</h1>
</header>
<script>
    if(window.location.pathname.includes("admin")){
        $("#header-title").text("CloudCatcher Admin");
        console.log("fires")
    }else{
        $("#header-title").text("CloudCatcher");
    }
</script>
