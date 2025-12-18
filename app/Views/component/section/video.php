<section class="video-section section section-md">
    <div class="container">
        <div class="section-title">
            <p class="subtitle section-description">Follow The Agenda</p>
            <h2 class="title section-heading">Video Tutorial Registrasi  dan Pengumpulan Proposal SheHacks 2025</h2>
        </div>

        <div class="row scrollable h-scrollable" id="load_data_video"></div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        // --------------------------- load data Video ---------------------------
            $.ajax({
                url      : '<?php echo base_url();?>fetch/fetch_data/fetch_data_video',
                dataType : 'json',
                beforeSend: function() {},
                success:function(response) {
                    var load_data = '';
                    
                    if (response.status == 1) {

                        $.each(response.data, function(i, val) {
                         
                            load_data += 
                                '<div class="col-6">'+
                                    '<div class="video-area">'+
                                        '<a href="javascript:void(0)" class="play-video play-video-showcase"><span></span></a>'+
                                        '<img data-src="https://img.youtube.com/vi/'+val.video+'/hqdefault.jpg" alt="" class="cover cover-video-showcase lazyload">'+
                                        '<video class="player" id="player-video-showcase" autoplay preload crossoriginx controls="false" controlslist="false" muted="false">'+
                                            '<source src="https://www.youtube.com/embed/'+val.video+'" type="video/mp4">'+
                                        '</video>'+
                                    '</div>'+
                                '</div>';
                        });

                        $('#load_data_video').html(load_data);
                    }

                    $('.lazyload').Lazy({
                        effect: 'fadeIn',
                        effectTime: 800,
                        threshold: 200,
                        visibleOnly: true,
                        combined: true,
                    });
                }
            });
        // --------------------------- end load data Video ---------------------------
    });
</script>