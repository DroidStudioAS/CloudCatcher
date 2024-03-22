<header class="app_header">
   <h1 id="header-title" class="header_title">CloudCatcher</h1>
    @if(\App\Models\User::isAdmin())
      <p class="admin_panel_link">
          Admin<br> Panel
      </p>
    @endif
</header>
<script>
    if(window.location.pathname.includes("admin")){
        $("#header-title").text("CloudCatcher Admin");
        console.log("fires")
    }else{
        $("#header-title").text("CloudCatcher");
    }
    $("h1").on("click",function (){
        location.href="/weather";
    })
    $("p").on('click',function (){
        if(window.location.pathname!=="/admin") {
            location.href = "/admin";
        }
    })
</script>
