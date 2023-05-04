<div class="w-100 h-100">
    <header id="cover">
        <div class="container-fluid h-100 d-flex flex-column justify-content-end align-items-end">
            <div class="flex-grow-1 d-flex justify-content-center align-items-center w-100">
                <div id="banner-site-title" class="w-100 text-center wow fadeIn" style="color: #ffffff" data-wow-duration="1.2s">Cook. Share. Enjoy.</div>
            </div>
            
        </div>
    </header>
    <div class="flex-grow-1 bg-light mb-0">
        <section class="wow slideInRight"  data-wow-delay=".5s" data-wow-duration="1.5s">
            <div class="container">
                <?php echo html_entity_decode(file_get_contents('./welcome.html')) ?>
            </div>
        </section>
    </div>
</div>
<script>
    $(document).scroll(function() { 
        $('#topNavBar').removeClass('bg-transaparent bg-dark')
        if($(window).scrollTop() === 0) {
           $('#topNavBar').addClass('bg-transaparent')
        }else{
           $('#topNavBar').addClass('bg-dark')
        }
    });
    $(function(){
        $(document).trigger('scroll')
    })
</script>