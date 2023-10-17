<div class="container">
    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <?php
                                    if (isset($_SESSION['info'])) {
                                    ?>
                                        <div class="alert alert-success text-center">
                                            <?php echo $_SESSION['info']; ?>
                                        </div>
                                    <?php
                                    } else {
                                    }
                                    ?>
                                    <?= $this->session->flashdata('message'); ?>
                                </div>
                                <form class="user" action="<?= base_url('auth/reset_password'); ?>" method="POST" autocomplete="off">
                                    <h4 class="text-center">Code Verification</h4>

                                    <div class="form-group">
                                        <input class="form-control" type="password" name="password" placeholder="Masukkan Password anda">
                                        <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="password" name="password2" placeholder="Konfirmasi password">
                                    </div>


                                    <button type="submit" name="reset_pass" class="btn btn-primary btn-user btn-block">
                                        Submit
                                    </button>

                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth') ?>">Already have an account? Login!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>