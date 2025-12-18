<section class="hero-section style-1 hero-md section">
    <img data-src="<?php echo base_url();?>assets/front/img/background/bg-hero.webp" alt="cover" class="img-fluid lazyload hero-cover">
    <div class="container">
        <div class="content center">
            <div class="row">
                <div class="col-lg-8 m-auto px-lg-5 px-1">
                    <h2 class="title section-heading-lg">Laporan Dampak 5 Tahun SheHacks: Perjalanan Memberdayakan Perempuan Indonesia di Bidang Teknologi</h2>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="section section-lg">
    <div class="container">
        <div class="el1-area">
            <div class="content">
                <img src="<?php echo base_url();?>assets/front/img/Thumbnail-impact-report.png" alt="banner" class="img-fluid lazyload thumb-img mb-5">
                <div class="section-title mb-1">
                    <h2 class="title section-description-lg">Sejak 2020, SheHacks telah menjangkau lebih dari 34.882 entrepreneur perempuan di 203 kota/kabupaten di seluruh Indonesia.</h2>
                </div>
            
                <p>Melalui program ini, ribuan perempuan mendapatkan akses ke mentorship, pelatihan keterampilan digital, serta peluang pendanaan untuk mengembangkan bisnis mereka.</p>

                <p>Namun, pencapaian ini juga mengungkap berbagai tantangan yang tidak hanya dihadapi oleh peserta SheHacks. Masih banyak entrepreneur perempuan yang belum memiliki keterampilan teknologi tingkat lanjut seperti artificial intelligence (AI), blockchain, dan fintech, yang penting dalam mendorong pertumbuhan bisnis digital. Di sisi lain, akses terhadap pendanaan juga masih terbatas, baik karena kurangnya kesiapan investasi maupun belum tersedianya mekanisme pendanaan yang berpihak pada kebutuhan perempuan.</p>

                <p>Meski dukungan dalam bentuk mentorship dan pelatihan sudah semakin berkembang, perempuan pelaku usaha masih menghadapi hambatan struktural—terutama karena belum banyak kebijakan atau sistem investasi yang responsif gender.                </p>
                
                <div class="my-4">
                    <div class="section-title mb-0">
                        <h2 class="title section-description-lg">Apa yang dibahas dalam laporan ini?</h2>
                    </div>
                    <ul class="list-dots">
                        <li>Kesenjangan akses terhadap teknologi dan pelatihan digital,</li>
                        <li>Tantangan dalam mendapatkan pendanaan yang adil dan inklusif,</li>
                        <li>Perlunya kebijakan dan ekosistem pendukung yang memberi ruang bagi kepemimpinan dan kemandirian perempuan pelaku usaha.</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <div class="section-title mb-0">
                        <h2 class="title section-description-lg">Apa langkah selanjutnya?</h2>
                    </div>
                    <ul class="list-dots">
                        <li>Mendorong lahirnya lebih banyak skema <b>pendanaan berperspektif gender (<i>gender-lens investing</i>)</b></li>
                        <li>Memperkuat pelatihan teknologi digital lanjutan untuk perempuan</li>
                        <li>Menerapkan model mentorship berlapis yang menghubungkan peserta dengan mentor dan praktisi industri terbaik</li>
                    </ul>
                </div>
                
                <h2 class="title section-description-md">Inklusivitas dimulai dari sini.                </h2>
                <p>Unduh <i>SheHacks 5-Year Impact Report</i> dan mari bersama membangun ekosistem wirausaha perempuan yang lebih inklusif dan setara!</p>
                <a href="javascript:void(0)" target="_blank" class="btn btn-hover-icon-left trigg-unduh-doc"><span class="icon lni lni-download"></span> <span>Unduh</span></a>
            </div>

            <div class="sidebar">
                <div class="sidebar-widget">
                    <img src="<?php echo base_url();?>assets/front/img/Milestone-impact-report.png" alt="banner" class="img-fluid lazyload thumb-img milestone-img">
                </div>

                <div class="sidebar-widget">
                    <h3 class="sidebar-title section-description-lg">Shehacks Pillars</h3>

                    <div class="list-check list-check-2">
                        <div class="list-item">
                            <h4 class="title section-description mb-0">Business</h4>
                            <p>Sustainability</p>
                        </div>
                        <div class="list-item">
                            <h4 class="title section-description mb-0">Digital</h4>
                            <p>Inovation</p>
                        </div>
                        <div class="list-item">
                            <h4 class="title section-description mb-0">Women</h4>
                            <p>Empowerment</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
	$(document).ready(function () {

        $(document).on('click','.trigg-unduh-doc',function(e) {
            e.preventDefault();

            trigger_cta_event('Unduh Dokumen Impact Report');
            window.open("<?php echo base_url();?>file_media/Doc-SheHacks-Impact-Report.pdf", '_blank');
        });

        function trigger_cta_event(data){
            var url_visit = getUrlVisit();
            $.ajax({
                 method   : 'POST',
                 url      : '<?php echo base_url();?>analytic/post_cta_btn',
                 data     : { data:data, url:url_visit },
                 dataType : 'json',
                 success:function(response) {

                 }
            });
            return false;
        }

    });
</script>
