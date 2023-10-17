 <!-- Bootstrap core JavaScript-->


 <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
 <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

 <!-- Core plugin JavaScript-->
 <script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

 <!-- Custom scripts for all pages-->
 <script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
 <script type="text/javascript">
     <?php if ($this->session->flashdata('success')) { ?>
         let pesan = "<?= $this->session->flashdata('success') ?>";
         Swal.fire({
             position: 'center',
             type: 'success',
             title: pesan,
             showConfirmButton: false,
             timer: 1500
         })
     <?php } ?>
 </script>
 </body>

 </html>