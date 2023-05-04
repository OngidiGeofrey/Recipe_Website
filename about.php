<style>
    h1{
        font-family:Brush Script MT, Brush Script Std, cursive;
        font-style:italic;
        
    }
    #content{
        display:none;
    }
</style>
<div class="container py-5 mt-4">
    <h1 class="text-center strikeBg wow slideInRight"
    style="text-align: center; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 35px; color: #05c118;">
    <a href="#" style="text-decoration: none; color: inherit;" title="About Us" 
    onmouseover="this.style.color='#ff6600';" onmouseout="this.style.color='#03a608';">About Us</a>
  </h1> 
    <div id="content">
        <?php echo html_entity_decode(file_get_contents('about.html'))?>
    </div>
</div>
<script>
$(function(){
    var content = $('#content')
    var cloned = content.clone()
    var el = $('<div id="content-show">')
    cloned.find('p,h1,h2,h3,h4,h5,ul,ol').each(function(){
        $(this).addClass("wow fadeIn")
    })
    el.append(cloned.html())
    content.replaceWith(el)
})
</script>
