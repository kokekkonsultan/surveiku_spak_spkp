

<?php
$ci = get_instance();
?>

<?php $__env->startSection('style'); ?>
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="container mt-5 mb-5" style="font-family: nunito;">
    <div class="text-center" data-aos="fade-up">
        <div id="progressbar" class="mb-5">
            <li id="account"><strong>Data Responden</strong></li>
            <li id="personal"><strong>Pertanyaan Survei</strong></li>
            <?php if($status_saran == 1): ?>
            <li id="payment"><strong>Saran</strong></li>
            <?php endif; ?>
            <li id="completed"><strong>Completed</strong></li>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow" data-aos="fade-up" id="root">
                <?php if($judul->img_benner == ''): ?>
                <img class="card-img-top" src="<?php echo e(base_url()); ?>assets/img/site/page/banner-survey.jpg"
                    alt="new image" />
                <?php else: ?>
                <img class="card-img-top shadow"
                    src="<?php echo e(base_url()); ?>assets/klien/benner_survei/<?php echo e($manage_survey->img_benner); ?>" alt="new image">
                <?php endif; ?>

                <div class="card-body">
                    <div>
                        <?php
                        $slug = $ci->uri->segment(2);

                        $data_user = $ci->db->query("SELECT *
                        FROM manage_survey
                        JOIN users u ON manage_survey.id_user = u.id
                        WHERE slug = '$slug'")->row();
                        ?>

                        <?php echo $data_user->deskripsi_opening_survey; ?>

                    </div>
                    <br><br>
                    <?php if($ci->uri->segment(3) == NULL): ?>
                    <?php echo anchor(base_url() . 'survei/' . $ci->uri->segment(2) . '/data-responden', 'IKUT SURVEI',
                    ['class' => 'btn btn-warning btn-block font-weight-bold shadow']); ?>

                    <?php else: ?>
                    <?php echo anchor(base_url() . 'survei/' . $ci->uri->segment(2) . '/data-responden/' .
                    $ci->uri->segment(3), 'IKUT SURVEI', ['class' => 'btn btn-warning btn-block']); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react/16.8.6/umd/react.production.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/react-dom/16.8.6/umd/react-dom.production.min.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>


<?php if($manage_survey->img_form_opening == ''): ?>
<script>
function get_canvas() {
    // $(document).ready(function() {

    // document.getElementById("btn_convert").addEventListener("click", function() {

    html2canvas(document.getElementById("root"), {
        allowTaint: true,
        useCORS: true
    }).then(function(canvas) {
        var anchorTag = document.createElement("a");
        document.body.appendChild(anchorTag);

        var dataURL = canvas.toDataURL();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . 'survei/' . $ci->uri->segment(2) . '/form-opening/convert' ?>",
            data: {
                imgBase64: dataURL
            },
            beforeSend: function() {},
            complete: function() {}
        }).done(function(o) {

        });
    });
    // });
    // });
};
setTimeout(get_canvas, 2500);
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('include_backend/_template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp-7.3.33\htdocs\surveiku_spak_spkp\application\views/survei/form_opening.blade.php ENDPATH**/ ?>