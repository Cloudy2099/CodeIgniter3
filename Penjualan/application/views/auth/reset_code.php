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
                                <form class="user" action="<?= base_url('auth/reset_code'); ?>" method="POST" autocomplete="off">
                                    <h4 class="text-center">Code Verification</h4>

                                    <div class="form-group">
                                        <input class="form-control" type="number" name="reset-otp" placeholder="Enter verification code" required>
                                    </div>

                                    <button type="submit" name="check-reset" class="btn btn-primary btn-user btn-block">
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