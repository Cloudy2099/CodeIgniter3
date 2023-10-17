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

                                    <?= $this->session->flashdata('message'); ?>
                                </div>
                                <form class="user" action="<?= base_url('auth/forgot'); ?>" method="POST" autocomplete="off">
                                    <h2 class="text-center">Forgot Password</h2>
                                    <p class="text-center">Enter your email address</p>

                                    <div class="form-group">
                                        <input class="form-control" type="text" name="check-email" placeholder="Masukkan Email Anda" value="<?= set_value('check-email') ?>">
                                        <?= form_error('check-email', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>

                                    <button type="submit" name="check" class="btn btn-primary btn-user btn-block">
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