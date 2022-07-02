<?php echo form_open("/hosts/edit/$host_item->host_id", array("class" => "form"), array("host_id" => $host_item->host_id)); ?>
<div class="container">
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <h1 class="display-6"><?php echo lang("Kea.hosts_msg_edithost"); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="form-group">
                <label for="host_ip"><?php echo lang("Kea.hosts_ipaddr"); ?></label>
                <input class="form-control" name="host_ip" type="text" value="<?php echo set_value("host_ip", $host_item->host_ip); ?>" readonly />
                <span class="text-danger font-weight-bold">
                    <?php echo $validation->getError("host_ip"); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="form-group">
                <label for="host_mac"><?php echo lang("Kea.hosts_macaddr"); ?></label>
                <input class="form-control" name="host_mac" type="text" value="<?php echo set_value("host_mac", $host_item->host_mac); ?>" />
                <span class="text-danger font-weight-bold">
                    <?php echo $validation->getError("host_mac"); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="form-group">
                <label for="hostname"><?php echo lang("Kea.hosts_descr"); ?></label>
                <input class="form-control" name="hostname" type="text" value="<?php echo set_value("hostname", $host_item->hostname); ?>" />
                <span class="text-danger font-weight-bold">
                    <?php echo $validation->getError("hostname"); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary my-1" name="submit"><?php echo lang("Kea.hosts_msg_edithost"); ?></button>
                <button type="button" class="btn btn-outline-primary my-1" onclick="location.href=" /hosts""><?php echo lang("Kea.hosts_cancel"); ?></button>
            </div>
        </div>
    </div>
</div>
</form>