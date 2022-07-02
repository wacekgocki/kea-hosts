<div class="container">
    <div class="row mt-4">
        <div class="col col-lg-6 offset-lg-3">
            <div class="jumbotron">
                <h1 class="display-5"><?php echo lang("Kea.app_msg_login"); ?></h1>
                <p class="lead"><?php echo lang("Kea.app_msg_login_sub"); ?></p>
            </div>
        </div>
    </div>
    <?php echo form_open("/login", "class=\"form\""); ?>
    <div class="row my-2">
        <div class="col col-lg-6 offset-lg-3">
            <div class="form-group">
                <label for="username"><?php echo lang("Kea.app_username"); ?></label>
                <input class="form-control" name="username" type="text" placeholder="<?php echo lang("Kea.app_msg_username"); ?>" value="<?php echo $username; ?>" />
                <span class="align-middle text-danger font-weight-bold">
                    <?php echo $validation->getError("username"); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row my-2">
        <div class="col col-lg-6 offset-lg-3">
            <div class="form-group">
                <label for="password"><?php echo lang("Kea.app_password"); ?></label>
                <input class="form-control" name="password" type="password" placeholder="<?php echo lang("Kea.app_msg_password"); ?>" value="<?php echo $password; ?>" />
                <span class="text-danger font-weight-bold">
                    <?php echo $validation->getError("password"); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row my-2">
        <div class="col col-lg-6 offset-lg-3">
            <button class="btn btn-primary mr-2" type="submit"><?php echo lang("Kea.app_login"); ?></button>
        </div>
    </div>
    </form>
</div>